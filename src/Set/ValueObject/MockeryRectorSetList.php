<?php

declare(strict_types=1);

namespace Ghostwriter\MockeryRector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final readonly class MockeryRectorSetList implements SetListInterface
{
    final public const PHPUNIT_TO_MOCKERY = __DIR__ . '/../../../config/sets/phpunit-to-mockery.php';

    final public const PROPHECY_TO_MOCKERY = __DIR__ . '/../../../config/sets/prophecy-to-mockery.php';

    final public const UPDATE_V1_6_X = __DIR__ . '/../../../config/sets/1.6.x.php';

    final public const UPDATE_V2_0_X = __DIR__ . '/../../../config/sets/2.0.x.php';
}
