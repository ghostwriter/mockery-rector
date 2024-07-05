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
 * @see \Tests\Unit\Rule\ProphecyToMockeryRectorTest
 */
final class ProphecyToMockeryRector extends AbstractMockeryRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            description: 'Refactor Prophecy to Mockery',
            codeSamples: [
                new CodeSample(
                    badCode: <<<'CODE_SAMPLE'
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
                    goodCode: <<<'CODE_SAMPLE'
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
