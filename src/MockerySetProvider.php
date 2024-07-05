<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Override;
use Rector\Set\Contract\SetInterface;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\ComposerTriggeredSet;

final readonly class MockerySetProvider implements SetProviderInterface
{
    public const string GROUP_NAME = 'mockery';

    public const string MOCKERY_1_6 = __DIR__ . '/../config/up-to-mockery-1.6.php';

    public const string MOCKERY_2_0 = __DIR__ . '/../config/up-to-mockery-2.0.php';

    public const string PACKAGE_NAME = 'mockery/mockery';

    /**
     * @return list<SetInterface>
     */
    #[Override]
    public function provide(): array
    {
        return [
            new ComposerTriggeredSet(
                groupName: self::GROUP_NAME,
                packageName: self::PACKAGE_NAME,
                version: '1.6',
                setFilePath: self::MOCKERY_1_6
            ),
            new ComposerTriggeredSet(
                groupName: self::GROUP_NAME,
                packageName: self::PACKAGE_NAME,
                version: '2.0',
                setFilePath: self::MOCKERY_2_0
            ),
        ];
    }
}
