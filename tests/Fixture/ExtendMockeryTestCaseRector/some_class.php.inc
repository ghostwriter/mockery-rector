<?php

declare(strict_types=1);

namespace Vendor\Package\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test()
    {
        $mock = Mockery::mock(Example::class);

        self::assertInstanceOf(Example::class, $mock);
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Vendor\Package\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery;

final class ExampleTest extends MockeryTestCase
{
    public function test()
    {
        $mock = Mockery::mock(Example::class);

        self::assertInstanceOf(Example::class, $mock);
    }
}
?>
