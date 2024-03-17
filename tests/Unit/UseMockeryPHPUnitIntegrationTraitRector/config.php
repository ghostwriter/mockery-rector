<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Rule\UseMockeryPHPUnitIntegrationTraitRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([UseMockeryPHPUnitIntegrationTraitRector::class]);
