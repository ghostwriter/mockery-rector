<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Upgrade\ReplaceHamcrestWithPHPUnitRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ReplaceHamcrestWithPHPUnitRector::class]);
