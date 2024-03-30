<?php

namespace Vendor\Package\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test(): void
    {
        $mock = Mockery::mock(TestCase::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
-----
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
        $mock = Mockery::mock(TestCase::class);

        self::assertInstanceOf(TestCase::class, $someMock);
    }
}
