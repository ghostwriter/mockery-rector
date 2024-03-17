<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\MockeryRectorLevelSetList;
use Ghostwriter\MockeryRector\MockeryRectorSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withSets(
    [MockeryRectorSetList::MOCKERY_20, MockeryRectorLevelSetList::UP_TO_MOCKERY_16]
);
