<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Tests\Unit\Rule\HamcrestToPHPUnitRectorTest
 */
final class HamcrestToPHPUnitRector extends AbstractMockeryRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Hamcrest matchers to PHPUnit constraints', [
            new CodeSample(
                <<<'CODE_SAMPLE'
                    // @todo fill code before
                    CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
                    // @todo fill code after
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
