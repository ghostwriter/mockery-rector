<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\MockeryRectorSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withSets([MockeryRectorSetList::MOCKERY_16]);
