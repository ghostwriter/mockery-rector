<?php

declare(strict_types=1);

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test()
    {
        $mock = $this->createMock(Example::class);

        self::assertInstanceOf(Example::class, $mock);
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Vendor\Package\Tests;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test()
    {
        $mock = $this->createMock(Example::class);

        self::assertInstanceOf(Example::class, $mock);
    }
}
?>
