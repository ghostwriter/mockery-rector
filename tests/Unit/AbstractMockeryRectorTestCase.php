<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use ReflectionClass;
use function dirname;

abstract class AbstractMockeryRectorTestCase extends AbstractRectorTestCase
{
    final public function provideConfigFilePath(): string
    {
        return self::currentDirectory('/config/configured_rule.php');
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
