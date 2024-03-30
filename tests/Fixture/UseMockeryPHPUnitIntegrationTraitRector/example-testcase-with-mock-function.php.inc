<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;
use function mock;

final class ExampleTest extends TestCase
{
    public function test(): void
    {
        $mock = mock(TestCase::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
-----
<?php

namespace Vendor\Package\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use function mock;

final class ExampleTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    public function test(): void
    {
        $mock = mock(TestCase::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
