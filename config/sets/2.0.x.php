<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Set\ValueObject\MockeryRectorSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withSets([MockeryRectorSetList::UPDATE_V1_6_X]);
