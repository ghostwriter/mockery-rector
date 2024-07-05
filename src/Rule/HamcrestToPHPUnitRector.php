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
 * @see \Tests\Unit\Rule\HamcrestToPHPUnitRectorTest
 */
final class HamcrestToPHPUnitRector extends AbstractMockeryRector
{
    #[Override]
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            description: 'Refactor Hamcrest matchers to PHPUnit constraints',
            codeSamples: [
                new CodeSample(
                    badCode: <<<'CODE_SAMPLE'
                        // @todo fill code before
                        CODE_SAMPLE
                    ,
                    goodCode: <<<'CODE_SAMPLE'
                        // @todo fill code after
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
