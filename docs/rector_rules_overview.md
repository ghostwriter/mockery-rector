# 7 Rules Overview

## ExtendMockeryTestCaseRector

Refactor to extend `Mockery\Adapter\Phpunit\MockeryTestCase` class when using Mockery

- class: [`Ghostwriter\MockeryRector\Rule\ExtendMockeryTestCaseRector`](../src/Rule/ExtendMockeryTestCaseRector.php)

```diff
 <?php

 declare(strict_types=1);

 namespace Vendor\Package\Tests;

+use Mockery\Adapter\Phpunit\MockeryTestCase;
 use Mockery;
-use PHPUnit\Framework\TestCase;

-final class ExampleTest extends TestCase
+final class ExampleTest extends MockeryTestCase
 {
     public function test()
     {
         $mock = Mockery::mock(Example::class);

         self::assertInstanceOf(Example::class, $mock);
     }
 }
```

<br>

## HamcrestToPHPUnitRector

Refactor Hamcrest matchers to PHPUnit constraints

- class: [`Ghostwriter\MockeryRector\Rule\HamcrestToPHPUnitRector`](../src/Rule/HamcrestToPHPUnitRector.php)

```diff
-// @todo fill code before
+// @todo fill code after
```

<br>

## PHPUnitToMockeryRector

Refactor PHPUnit to Mockery

- class: [`Ghostwriter\MockeryRector\Rule\PHPUnitToMockeryRector`](../src/Rule/PHPUnitToMockeryRector.php)

```diff
 <?php

 namespace Vendor\Package\Tests;

 use PHPUnit\Framework\TestCase;

 final class ExampleTest extends TestCase
 {
     public function test()
     {
-        $mock = $this->createStub(Example::class);
+        $mock = \Mockery::mock(Example::class);

-        $mock->method('method')->willReturn('value');
+        $mock->expects('method')->andReturn('value');

         self::assertSame('value', $mock->method());
     }
 }
```

<br>

## ProphecyToMockeryRector

Refactor Prophecy to Mockery

- class: [`Ghostwriter\MockeryRector\Rule\ProphecyToMockeryRector`](../src/Rule/ProphecyToMockeryRector.php)

```diff
 <?php

 declare(strict_types=1);

 namespace Vendor\Package\Tests;

 use PHPUnit\Framework\TestCase;

 final class ExampleTest extends TestCase
 {
     public function test()
     {
-        $mock = $this->prophesize(Example::class);
+        $mock = \Mockery::mock(Example::class);

-        self::assertInstanceOf(Example::class, $mock->reveal());
+        self::assertInstanceOf(Example::class, $mock);
     }
 }
```

<br>

## ShouldReceiveToAllowsRector

Refactor `shouldReceive()` to `allows()` static method call

- class: [`Ghostwriter\MockeryRector\Rule\ShouldReceiveToAllowsRector`](../src/Rule/ShouldReceiveToAllowsRector.php)

```diff
 <?php

 namespace Vendor\Package\Tests;

 use PHPUnit\Framework\TestCase;

 final class ExampleTest extends TestCase
 {
     use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

     public function test(): void
     {
         $mock = \Mockery::mock(Example::class);

-        $mock->shouldReceive('method')->with('arg')->andReturn('value');
+        $mock->allows('method')->with('arg')->andReturn('value');

         self::assertSame('value', $mock->method('arg'));
     }
 }
```

<br>

## ShouldReceiveToExpectsRector

Refactor `shouldReceive()` to `expects()` static method call

- class: [`Ghostwriter\MockeryRector\Rule\ShouldReceiveToExpectsRector`](../src/Rule/ShouldReceiveToExpectsRector.php)

```diff
 <?php

 namespace Vendor\Package\Tests;

 use PHPUnit\Framework\TestCase;

 final class ExampleTest extends TestCase
 {
     use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

     public function test(): void
     {
         $mock = \Mockery::mock(Example::class);

-        $mock->shouldReceive('method')->once()->with('arg')->andReturn('value');
+        $mock->expects('method')->with('arg')->andReturn('value');

         self::assertSame('value', $mock->method('arg'));
     }
 }
```

<br>

## UseMockeryPHPUnitIntegrationTraitRector

Refactor to use `\Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration` trait when using Mockery

- class: [`Ghostwriter\MockeryRector\Rule\UseMockeryPHPUnitIntegrationTraitRector`](../src/Rule/UseMockeryPHPUnitIntegrationTraitRector.php)

```diff
 <?php

 namespace Vendor\Package\Tests;

+use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
 use Mockery;
 use PHPUnit\Framework\TestCase;

 final class ExampleTest extends TestCase
 {
+    use MockeryPHPUnitIntegration;
     public function test(): void
     {
         $mock = Mockery::mock(Example::class);

         self::assertInstanceOf(Example::class, $mock);
     }
 }
```

<br>
