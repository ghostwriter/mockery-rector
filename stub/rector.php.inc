<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use Ghostwriter\MockeryRector\AbstractMockeryRector;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\Rule\RuleRectorTest
 */
final class RuleRector extends AbstractMockeryRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('// @todo rule-rector', [
            new CodeSample(
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

                        // @todo fill code before
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

                        // @todo fill code after
                    }
                }
                CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // @todo select node type
        return [Class_::class];
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
