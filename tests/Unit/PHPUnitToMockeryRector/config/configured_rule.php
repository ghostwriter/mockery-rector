<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Migrate\PHPUnitToMockeryRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([PHPUnitToMockeryRector::class]);
