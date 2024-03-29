<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Rule\ShouldReceiveToAllowsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ShouldReceiveToAllowsRector::class]);
