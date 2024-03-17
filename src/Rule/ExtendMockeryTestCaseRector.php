<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see ExtendMockeryTestCaseRectorTest
 */
final class ExtendMockeryTestCaseRector extends AbstractMockeryRector
{
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
                use PHPUnit\Framework\TestCase;

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
        // @todo change the node

        return $node;
    }
}
