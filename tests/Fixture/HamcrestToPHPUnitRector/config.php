<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Rule\HamcrestToPHPUnitRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([HamcrestToPHPUnitRector::class]);
