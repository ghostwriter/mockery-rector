<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Tests\Unit\Rule\PHPUnitToMockeryRectorTest
 */
final class PHPUnitToMockeryRector extends AbstractMockeryRector
{
    /**
     * @throws PoorDocumentationException
     */
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            description: 'Refactor PHPUnit to Mockery',
            codeSamples: [
                new CodeSample(
                    badCode: <<<'CODE_SAMPLE'
                        <?php

                        namespace Vendor\Package\Tests;

                        use PHPUnit\Framework\TestCase;

                        final class ExampleTest extends TestCase
                        {
                            public function test()
                            {
                                $mock = $this->createStub(Example::class);

                                $mock->method('method')->willReturn('value');

                                self::assertSame('value', $mock->method());
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
                            public function test()
                            {
                                $mock = \Mockery::mock(Example::class);

                                $mock->expects('method')->andReturn('value');

                                self::assertSame('value', $mock->method());
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
