<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector;

use Rector\Set\Contract\SetListInterface;

final readonly class MockeryRectorSetList implements SetListInterface
{
    final public const MOCKERY_16 = __DIR__ . '/../config/mockery-16.php';

    final public const MOCKERY_20 = __DIR__ . '/../config/mockery-20.php';

    final public const PHPUNIT_TO_MOCKERY = __DIR__ . '/../config/phpunit-to-mockery.php';

    final public const PROPHECY_TO_MOCKERY = __DIR__ . '/../config/prophecy-to-mockery.php';
}
