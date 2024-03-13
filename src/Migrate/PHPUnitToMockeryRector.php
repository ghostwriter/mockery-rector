<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Migrate;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ghostwriter\MockeryRectorTests\Unit\PHPUnitToMockeryRector\PHPUnitToMockeryRectorTest
 */
final class PHPUnitToMockeryRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // @todo select node type
        return [Class_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace PHPUnit test doubles with Mockery mocks.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    public function test()
    {
        $someMock = $this->createMock(SomeRepository::class);

        self::assertInstanceOf(SomeRepository::class, $someMock);
    }
}
?>
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class SomeTest extends TestCase
{
    public function test()
    {
        $someMock = \Mockery::mock(SomeRepository::class);

        self::assertInstanceOf(SomeRepository::class, $someMock);
    }
}
?>
CODE_SAMPLE
                ),
            ]
        );
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
