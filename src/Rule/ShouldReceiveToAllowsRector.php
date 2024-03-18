<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\LegacyMockerySyntaxRector\LegacyMockerySyntaxRectorTest
 */
final class ShouldReceiveToAllowsRector extends AbstractRector
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
        return new RuleDefinition('Refactor `shouldReceive()` to `allows()` static method call', [
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

                        $mock->shouldReceive('method')->with('arg')->andReturn('value');

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

                    $mock->allows('method')->with('arg')->andReturn('value');

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
