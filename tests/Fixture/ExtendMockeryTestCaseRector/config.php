<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Rule\ExtendMockeryTestCaseRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ExtendMockeryTestCaseRector::class]);
