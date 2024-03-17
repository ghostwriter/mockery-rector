<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Rule;

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
        return [Class_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactor PHPUnit to Mockery',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
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
                    <<<'CODE_SAMPLE'
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
    public function refactor(Node $node): ?Node
    {
        return $node;
    }
}
