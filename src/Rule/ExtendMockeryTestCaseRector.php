<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PHPUnit\Framework\TestCase;
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Tests\Unit\Rule\ExtendMockeryTestCaseRectorTest
 */
final class ExtendMockeryTestCaseRector extends AbstractMockeryRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactor to extend `Mockery\Adapter\Phpunit\MockeryTestCase` class when using Mockery',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
                        <?php

                        declare(strict_types=1);

                        namespace Vendor\Package\Tests;

                        use Mockery;
                        use PHPUnit\Framework\TestCase;

                        final class ExampleTest extends TestCase
                        {
                            public function test()
                            {
                                $mock = Mockery::mock(Example::class);

                                self::assertInstanceOf(Example::class, $mock);
                            }
                        }
                        CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
                        <?php

                        declare(strict_types=1);

                        namespace Vendor\Package\Tests;

                        use Mockery\Adapter\Phpunit\MockeryTestCase;
                        use Mockery;

                        final class ExampleTest extends MockeryTestCase
                        {
                            public function test()
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
     * @param FileWithoutNamespace|Namespace_ $node
     *
     * @throws ShouldNotHappenException
     */
    public function refactor(Node $node): ?Node
    {
        $refactored = false;

        $this->traverseNodes(
            $node->stmts,
            function (Node $node) use (&$refactored): ?Class_ {
                if (! $node instanceof Class_) {
                    return null;
                }

                if (! $this->isSubclassOfPHPUnitTestCase($node)) {
                    return null;
                }

                $refactored = true;

                $this->extend($node, MockeryTestCase::class);

                return $node;
            }
        );

        if (! $refactored) {
            return null;
        }

        $this->removeUseStatements(TestCase::class);

        return $node;
    }
}
