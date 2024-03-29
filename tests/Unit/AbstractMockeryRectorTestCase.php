<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRectorTests\Unit;

use Generator;
use Ghostwriter\MockeryRector\AbstractMockeryRector;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use ReflectionClass;
use function array_key_exists;
use function dirname;
use function realpath;
use function sprintf;
use function strrpos;
use function substr;

#[UsesClass(AbstractMockeryRector::class)]
abstract class AbstractMockeryRectorTestCase extends AbstractRectorTestCase
{
    protected static $filePaths = [];

    final public function provideConfigFilePath(): string
    {
        return self::fixtureDirectory('/config.php');
    }

    #[DataProvider('provideData')]
    final public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return string
     */
    public static function currentRuleName(): string
    {
        return substr(static::class, 1 + (strrpos(static::class, '\\') ?: -1), -4);
    }

    /**
     * @return Generator
     */
    final public static function provideData(): Generator
    {
        yield from self::yieldFilesFromDirectory(self::fixtureDirectory());
    }

    private static function fixtureDirectory(string $path = '/'): string
    {
        if (! array_key_exists(static::class, self::$filePaths)) {
            $filePath = dirname((new ReflectionClass(static::class))->getFileName());

            $ruleName = self::currentRuleName();

            $rulePath = '/tests/Fixture/' . $ruleName . '/';

            while (realpath($filePath . $rulePath) === false) {
                $filePath = dirname($filePath);

                if ($filePath === '/') {
                    throw new InvalidArgumentException(
                        sprintf(
                            'Fixture path for "%s" does not exist in %s or any parent directory',
                            $ruleName,
                            $rulePath
                        )
                    );
                }
            }

            self::$filePaths[static::class] = $filePath . $rulePath;
        }

        return self::$filePaths[static::class] . $path;
    }
}
