<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ghostwriter\MockeryRector\Rule\RuleRector;

return RectorConfig::configure()->withRules([RuleRector::class]);
