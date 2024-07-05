<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\MockeryLevelSetList;
use Ghostwriter\MockeryRector\MockerySetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([MockerySetList::MOCKERY_2_0, MockeryLevelSetList::UPGRADE_TO_MOCKERY_1_6]);
