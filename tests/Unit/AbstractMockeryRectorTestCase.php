<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit;

use Generator;
use Ghostwriter\MockeryRector\AbstractMockeryRector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use ReflectionClass;
use function dirname;

#[UsesClass(AbstractMockeryRector::class)]
abstract class AbstractMockeryRectorTestCase extends AbstractRectorTestCase
{
    final public function provideConfigFilePath(): string
    {
        return self::currentDirectory('/config.php');
    }

    #[DataProvider('provideData')]
    final public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Generator
     */
    final public static function provideData(): Generator
    {
        yield from self::yieldFilesFromDirectory(self::currentDirectory('/Fixture'));
    }

    private static function currentDirectory(string $path): string
    {
        return dirname((new ReflectionClass(static::class))->getFileName()) . $path;
    }
}
