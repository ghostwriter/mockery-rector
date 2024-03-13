<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Refactor\LegacyMockerySyntaxRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([LegacyMockerySyntaxRector::class]);
