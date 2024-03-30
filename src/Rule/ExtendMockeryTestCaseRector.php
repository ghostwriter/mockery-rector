<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\Rule\ExtendMockeryTestCaseRectorTest
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

                    use PHPUnit\Framework\TestCase;

                    final class ExampleTest extends TestCase
                    {
                        public function test()
                        {
                            $mock = \Mockery::mock(Example::class);

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

                    final class ExampleTest extends MockeryTestCase
                    {
                        public function test()
                        {
                            $mock = \Mockery::mock(Example::class);

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
            function (Node $node): null|Node {
                if (! $node instanceof Class_) {
                    return null;
                }

                if (! $this->isPHPUnitTestCase($node)) {
                    return null;
                }

                $this->removeUseStatements(TestCase::class);

                $node->extends = $this->importName(MockeryTestCase::class);

                return $node;
            }
        );

        return $node;
    }
}
