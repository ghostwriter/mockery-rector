<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Rule\ShouldReceiveToExpectsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ShouldReceiveToExpectsRector::class]);
