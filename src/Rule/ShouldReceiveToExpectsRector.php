<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\Rule\ShouldReceiveToExpectsRectorTest
 */
final class ShouldReceiveToExpectsRector extends AbstractMockeryRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor `shouldReceive()` to `expects()` static method call', [
            new CodeSample(
                <<<'CODE_SAMPLE'
                <?php

                namespace Vendor\Package\Tests;

                use PHPUnit\Framework\TestCase;

                final class ExampleTest extends TestCase
                {
                    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

                    public function test(): void
                    {
                        $mock = \Mockery::mock(Example::class);

                        $mock->shouldReceive('method')->once()->with('arg')->andReturn('value');

                        self::assertSame('value', $mock->method('arg'));
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
                        $mock = \Mockery::mock(Example::class);

                        $mock->expects('method')->with('arg')->andReturn('value');

                        self::assertSame('value', $mock->method('arg'));
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
