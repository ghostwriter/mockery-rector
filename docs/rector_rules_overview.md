# 5 Rules Overview

<br>

## Categories

- [Migrate](#migrate) (2)

- [Refactor](#refactor) (2)

- [Upgrade](#upgrade) (1)

<br>

## Migrate

### PHPUnitToMockeryRector

Replace PHPUnit test doubles with Mockery mocks.

- class: [`Ghostwriter\MockeryRector\Migrate\PHPUnitToMockeryRector`](../src/Migrate/PHPUnitToMockeryRector.php)

```diff
 <?php

 namespace Vendor\Package\Tests;

 use PHPUnit\Framework\TestCase;

 final class SomeTest extends TestCase
 {
     public function test()
     {
-        $someMock = $this->createMock(SomeRepository::class);
+        $someMock = \Mockery::mock(SomeRepository::class);

         self::assertInstanceOf(SomeRepository::class, $someMock);
     }
 }
 ?>
```

<br>

### ProphecyToMockeryRector

// `@todo` fill the description

- class: [`Ghostwriter\MockeryRector\Migrate\ProphecyToMockeryRector`](../src/Migrate/ProphecyToMockeryRector.php)

```diff
-// @todo fill code before
+// @todo fill code after
```

<br>

## Refactor

### AddMockeryPHPUnitIntegrationTraitRector

Add `\Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration` trait to PHPUnit test classes using Mockery

- class: [`Ghostwriter\MockeryRector\Refactor\AddMockeryPHPUnitIntegrationTraitRector`](../src/Refactor/AddMockeryPHPUnitIntegrationTraitRector.php)

```diff
 <?php

 namespace Vendor\Package\Tests;

 use PHPUnit\Framework\TestCase;

 final class ExampleTest extends TestCase
 {
+    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
+
     public function test(): void
     {
         $mock = \Mockery::mock(ExampleTest::class);

         self::assertInstanceOf(TestCase::class, $someMock);
     }
 }
```

<br>

### LegacyMockerySyntaxRector

// `@todo` fill the description

- class: [`Ghostwriter\MockeryRector\Refactor\LegacyMockerySyntaxRector`](../src/Refactor/LegacyMockerySyntaxRector.php)

```diff
-// @todo fill code before
+// @todo fill code after
```

<br>

## Upgrade

### ReplaceHamcrestWithPHPUnitRector

// `@todo` fill the description

- class: [`Ghostwriter\MockeryRector\Upgrade\ReplaceHamcrestWithPHPUnitRector`](../src/Upgrade/ReplaceHamcrestWithPHPUnitRector.php)

```diff
-// @todo fill code before
+// @todo fill code after
```

<br>
