<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Override;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\Set;

final readonly class MockeryLevelSetList implements SetProviderInterface
{
    public const string UPGRADE_TO_MOCKERY_1_6 = __DIR__ . '/../config/up-to-mockery-1.6.php';

    public const string UPGRADE_TO_MOCKERY_2_0 = __DIR__ . '/../config/up-to-mockery-2.0.php';

    /**
     * @return list<Set>
     */
    #[Override]
    public function provide(): array
    {
        /** @var null|list<Set> $sets */
        static $sets = null;

        return $sets ??= [
            new Set('Mockery', 'Upgrade to v1.6', self::UPGRADE_TO_MOCKERY_1_6),
            new Set('Mockery', 'Upgrade to v2.0', self::UPGRADE_TO_MOCKERY_2_0),
        ];
    }
}
