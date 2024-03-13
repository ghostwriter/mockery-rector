<?php

declare(strict_types=1);

use Ghostwriter\MockeryRector\Refactor\LegacyMockerySyntaxRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withRules([LegacyMockerySyntaxRector::class])
    ->withSets([
        SetList::PHP_52,
        SetList::PHP_53,
        SetList::PHP_54,
        SetList::PHP_55,
        SetList::PHP_56,
        SetList::PHP_70,
        SetList::PHP_71,
        SetList::PHP_72,
        SetList::PHP_73,
        SetList::PHP_73,
        LevelSetList::UP_TO_PHP_72,
    ]);
