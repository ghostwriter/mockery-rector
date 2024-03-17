<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\ProphecyToMockeryRector\ProphecyToMockeryRectorTest
 */
final class ProphecyToMockeryRector extends AbstractRector
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
        return new RuleDefinition('Refactor Prophecy to Mockery', [
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
                        $mock = $this->prophesize(Example::class);

                        self::assertInstanceOf(Example::class, $mock->reveal());
                    }
                }
                CODE_SAMPLE
                ,
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
            ),
        ]);
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        return $node;
    }
}
