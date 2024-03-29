<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\MockerySetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([MockerySetList::MOCKERY_1_6]);
