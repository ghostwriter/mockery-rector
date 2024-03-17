<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Rector\Set\Contract\SetListInterface;

final readonly class MockeryRectorLevelSetList implements SetListInterface
{
    final public const UP_TO_MOCKERY_16 = __DIR__ . '/../config/up-to-mockery-16.php';

    final public const UP_TO_MOCKERY_20 = __DIR__ . '/../config/up-to-mockery-20.php';
}
