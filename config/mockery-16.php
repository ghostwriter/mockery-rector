<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Rule\ExtendMockeryTestCaseRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToAllowsRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToExpectsRector;
use Ghostwriter\MockeryRector\Rule\UseMockeryPHPUnitIntegrationTraitRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()->withSets([SetList::PHP_73])->withRules([
    ExtendMockeryTestCaseRector::class,
    ShouldReceiveToAllowsRector::class,
    ShouldReceiveToExpectsRector::class,
    UseMockeryPHPUnitIntegrationTraitRector::class,
]);
