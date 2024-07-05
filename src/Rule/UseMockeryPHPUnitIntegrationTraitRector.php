<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Tests\Unit\Rule\UseMockeryPHPUnitIntegrationTraitRectorTest
 */
final class UseMockeryPHPUnitIntegrationTraitRector extends AbstractMockeryRector
{
    /**
     * @throws PoorDocumentationException
     */
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            description: 'Refactor to use `\Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration` trait when using Mockery',
            codeSamples: [
                new CodeSample(
                    badCode: <<<'CODE_SAMPLE'
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
                    goodCode: <<<'CODE_SAMPLE'
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
     * @param FileWithoutNamespace|Namespace_ $node
     *
     * @throws ShouldNotHappenException
     */
    #[Override]
    public function refactor(Node $node): ?Node
    {
        if (! $this->needsMockeryPHPUnitIntegrationTrait($node)) {
            return null;
        }

        return $this->addMockeryPHPUnitIntegrationTrait($node);
    }
}
