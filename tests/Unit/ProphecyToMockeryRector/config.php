<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Rule\ProphecyToMockeryRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ProphecyToMockeryRector::class]);
