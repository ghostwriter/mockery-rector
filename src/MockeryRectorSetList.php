<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Rector\Set\Contract\SetListInterface;

final readonly class MockeryRectorSetList implements SetListInterface
{
    final public const MOCKERY_1_6 = __DIR__ . '/../config/mockery-1.6.php';

    final public const MOCKERY_2_0 = __DIR__ . '/../config/mockery-2.0.php';

    final public const PHPUNIT_TO_MOCKERY = __DIR__ . '/../config/phpunit-to-mockery.php';

    final public const PROPHECY_TO_MOCKERY = __DIR__ . '/../config/prophecy-to-mockery.php';
}
