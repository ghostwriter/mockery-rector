<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Tests\Unit\Rule\ShouldReceiveToExpectsRectorTest
 */
final class ShouldReceiveToExpectsRector extends AbstractMockeryRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            description: 'Refactor `shouldReceive()` to `expects()` static method call',
            codeSamples: [
                new CodeSample(
                    badCode: <<<'CODE_SAMPLE'
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
                    goodCode: <<<'CODE_SAMPLE'
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

            ]
        );
    }

    /**
     * @param Class_ $node
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        return $node;
    }
}
