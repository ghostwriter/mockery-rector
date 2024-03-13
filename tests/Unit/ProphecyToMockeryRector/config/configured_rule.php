<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Migrate\ProphecyToMockeryRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ProphecyToMockeryRector::class]);
