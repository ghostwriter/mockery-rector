<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Override;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\Set;

final readonly class MockerySetList implements SetProviderInterface
{
    public const MOCKERY_1_6 = __DIR__ . '/../config/mockery-1.6.php';

    public const MOCKERY_2_0 = __DIR__ . '/../config/mockery-2.0.php';

    public const PHPUNIT_TO_MOCKERY = __DIR__ . '/../config/phpunit-to-mockery.php';

    public const PROPHECY_TO_MOCKERY = __DIR__ . '/../config/prophecy-to-mockery.php';

    /**
     * @return list<Set>
     */
    #[Override]
    public function provide(): array
    {
        /** @var list<Set>|null $sets */
        static $sets = null;

        return $sets ??= [
            new Set('Mockery', 'Mockery 1.6', self::MOCKERY_1_6),
            new Set('Mockery', 'Mockery 2.0', self::MOCKERY_2_0),
            new Set('Mockery', 'PHPUnit to Mockery', self::PHPUNIT_TO_MOCKERY),
            new Set('Mockery', 'Prophecy to Mockery', self::PROPHECY_TO_MOCKERY),
        ];
    }
}
