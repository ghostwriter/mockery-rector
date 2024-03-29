<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Rector\Set\Contract\SetListInterface;

final readonly class MockeryLevelSetList implements SetListInterface
{
    final public const UP_TO_MOCKERY_1_6 = __DIR__ . '/../config/up-to-mockery-1.6.php';

    final public const UP_TO_MOCKERY_2_0 = __DIR__ . '/../config/up-to-mockery-2.0.php';
}
