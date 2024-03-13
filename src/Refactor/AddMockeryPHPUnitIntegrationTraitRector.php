<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Refactor;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\TestCase;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_unshift;
use function count;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\AddMockeryPHPUnitIntegrationTraitRector\AddMockeryPHPUnitIntegrationTraitRectorTest
 */
final class AddMockeryPHPUnitIntegrationTraitRector extends AbstractRector
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add `\Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration` trait to PHPUnit test classes using Mockery',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test(): void
    {
        $mock = \Mockery::mock(ExampleTest::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function test(): void
    {
        $mock = \Mockery::mock(ExampleTest::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
CODE_SAMPLE
                ),

            ]
        );
    }

    public function isPHPUnitTestCase(Class_ $node): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);

        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->isSubclassOf(TestCase::class);
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof Class_ && ! $this->isPHPUnitTestCase($node)) {
            return null;
        }

        if ($this->hasMockeryPHPUnitIntegrationTrait($node)) {
            return null;
        }

        if (! $this->hasMockeryMock($node) && ! $this->hasMockFunction($node)) {
            return null;
        }

        if ($this->hasTestCaseTearDownMockeryCloseWithOptionalParentTearDown($node)) {
            $node = $this->removeTeardownClassMethod($node);
        }

        return $this->addMockeryPHPUnitIntegrationTrait($node);
    }

    private function addMockeryPHPUnitIntegrationTrait(Class_ $node): Class_
    {
        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof TraitUse) {
                continue;
            }

            foreach ($stmt->traits as $trait) {
                if ($this->isName($trait, MockeryPHPUnitIntegration::class)) {
                    return $node;
                }
            }
        }

        array_unshift($node->stmts, new TraitUse([new FullyQualified(MockeryPHPUnitIntegration::class)]));

        return $node;
    }

    private function hasMockFunction(Class_ $node): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof FuncCall) {
                return false;
            }

            return $this->isName($node->name, 'mock');
        });
    }

    private function hasMockeryClose(Node $node): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'Mockery')) {
                return false;
            }

            return $this->isName($node->name, 'close');
        });
    }

    private function hasMockeryCloseAndParentTearDown(ClassMethod $node): bool
    {
        return $this->hasMockeryClose($node) && $this->hasParentTearDown($node);
    }

    private function hasMockeryMock(Class_ $node): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'Mockery')) {
                return false;
            }

            return $this->isName($node->name, 'mock');
        });
    }

    private function hasMockeryPHPUnitIntegrationTrait(Class_ $node): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);

        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->hasTraitUse(MockeryPHPUnitIntegration::class);
    }

    private function hasParentTearDown(ClassMethod $node): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'parent')) {
                return false;
            }

            return $this->isName($node->name, 'tearDown');
        });
    }

    private function hasTestCaseTearDownMockeryCloseWithOptionalParentTearDown(Class_ $node): bool
    {
        return (bool) $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof ClassMethod) {
                return false;
            }

            if (! $this->isName($node->name, 'tearDown')) {
                return false;
            }

            $stmtsCount = count($node->stmts ?? []);

            if ($stmtsCount === 2) {
                return $this->hasMockeryCloseAndParentTearDown($node);
            }

            return $stmtsCount === 1 ? $this->hasMockeryClose($node) : false;
        });
    }

    private function removeClassMethod(Class_ $node, string $methodName): Class_
    {
        if (! $node instanceof Class_) {
            return $node;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof ClassMethod) {
                continue;
            }

            if (! $this->isName($stmt->name, $methodName)) {
                continue;
            }

            unset($node->stmts[$key]);

            break;
        }

        return $node;
    }

    private function removeTeardownClassMethod(Class_ $node): Class_
    {
        return $this->removeClassMethod($node, 'tearDown');
    }
}
