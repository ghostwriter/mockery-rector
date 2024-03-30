<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Reflection\ClassReflection;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use function array_unshift;
use function count;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\Rule\UseMockeryPHPUnitIntegrationTraitRectorTest
 */
final class UseMockeryPHPUnitIntegrationTraitRector extends AbstractMockeryRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactor to use `\Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration` trait when using Mockery',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
                    <?php

                    namespace Vendor\Package\Tests;

                    use Mockery;
                    use PHPUnit\Framework\TestCase;

                    final class ExampleTest extends TestCase
                    {
                        public function test(): void
                        {
                            $mock = Mockery::mock(Example::class);

                            self::assertInstanceOf(Example::class, $mock);
                        }
                    }
                    CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
                    <?php

                    namespace Vendor\Package\Tests;

                    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
                    use Mockery;
                    use PHPUnit\Framework\TestCase;

                    final class ExampleTest extends TestCase
                    {
                        use MockeryPHPUnitIntegration;
                        public function test(): void
                        {
                            $mock = Mockery::mock(Example::class);

                            self::assertInstanceOf(Example::class, $mock);
                        }
                    }
                    CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->traverseNodesWithCallable(
            $node->stmts,
            function (Node $node): ?Node {
                if (! $node instanceof Class_) {
                    return null;
                }

                if (! $this->isPHPUnitTestCase($node)) {
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
        );

        return $node;
    }

    private function addMockeryPHPUnitIntegrationTrait(Class_ $node): Class_
    {
        if ($this->hasTrait($node, MockeryPHPUnitIntegration::class)) {
            return $node;
        }

        array_unshift($node->stmts, new TraitUse([$this->importName(MockeryPHPUnitIntegration::class)]));

        return $node;
    }

    private function hasMockFunction(Class_ $node): bool
    {
        return $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof FuncCall) {
                return false;
            }

            return $this->isName($node->name, 'mock');
        }) instanceof Node;
    }

    private function hasMockeryClose(Node $node): bool
    {
        return $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'Mockery')) {
                return false;
            }

            return $this->isName($node->name, 'close');
        }) instanceof Node;
    }

    private function hasMockeryCloseAndParentTearDown(ClassMethod $node): bool
    {
        return $this->hasMockeryClose($node) && $this->hasParentTearDown($node);
    }

    private function hasMockeryMock(Class_ $node): bool
    {
        return $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'Mockery')) {
                return false;
            }

            return $this->isName($node->name, 'mock');
        }) instanceof Node;
    }

    private function hasMockeryPHPUnitIntegrationTrait(Class_ $node): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);

        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->hasTraitUse(MockeryPHPUnitIntegration::class);
        // return $this->hasTrait(MockeryPHPUnitIntegration::class);
    }

    private function hasParentTearDown(ClassMethod $node): bool
    {
        return $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
            if (! $node instanceof StaticCall) {
                return false;
            }

            if (! $this->isName($node->class, 'parent')) {
                return false;
            }

            return $this->isName($node->name, 'tearDown');
        }) instanceof Node;
    }

    private function hasTestCaseTearDownMockeryCloseWithOptionalParentTearDown(Class_ $node): bool
    {
        return $this->betterNodeFinder->findFirst($node, function (Node $node): bool {
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
        }) instanceof Node;
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
