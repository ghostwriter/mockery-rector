<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test(): void
    {
        $mock = \Mockery::mock(TestCase::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
-----
<?php

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
    public function test(): void
    {
        $mock = \Mockery::mock(TestCase::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
