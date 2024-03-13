<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Refactor\AddMockeryPHPUnitIntegrationTraitRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([AddMockeryPHPUnitIntegrationTraitRector::class]);
