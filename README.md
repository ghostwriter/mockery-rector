# Mockery Rector

[![Compliance](https://github.com/ghostwriter/mockery-rector/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/mockery-rector/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/mockery-rector?color=8892bf)](https://www.php.net/supported-versions)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/mockery-rector&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Code Coverage](https://codecov.io/gh/ghostwriter/mockery-rector/branch/main/graph/badge.svg)](https://codecov.io/gh/ghostwriter/mockery-rector)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/mockery-rector/coverage.svg)](https://shepherd.dev/github/ghostwriter/mockery-rector)
[![Psalm Level](https://shepherd.dev/github/ghostwriter/mockery-rector/level.svg)](https://psalm.dev/docs/running_psalm/error_levels)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/mockery-rector)](https://packagist.org/packages/ghostwriter/mockery-rector)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/mockery-rector?color=blue)](https://packagist.org/packages/ghostwriter/mockery-rector)

Rector extension for Mockery

## Star ⭐️ this repo if you find it useful

You can also star (🌟) this repo to find it easier later.

## Installation

You can install the package via composer:

``` bash
composer require rector/rector --dev
composer require ghostwriter/mockery-rector --dev
```

## Usage

To add a set to your config, and pick one of constants:

- `Ghostwriter\MockeryRector\MockeryRectorSetList`
- `Ghostwriter\MockeryRector\MockeryRectorLevelSetList`

```php
use Ghostwriter\MockeryRector\MockeryLevelSetList;
use Ghostwriter\MockeryRector\MockerySetList;
use Ghostwriter\MockeryRector\Rule\ExtendMockeryTestCaseRector;
use Ghostwriter\MockeryRector\Rule\HamcrestToPHPUnitRector;
use Ghostwriter\MockeryRector\Rule\PHPUnitToMockeryRector;
use Ghostwriter\MockeryRector\Rule\ProphecyToMockeryRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToAllowsRector;
use Ghostwriter\MockeryRector\Rule\ShouldReceiveToExpectsRector;
use Ghostwriter\MockeryRector\Rule\UseMockeryPHPUnitIntegrationTraitRector;

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        // ExtendMockeryTestCaseRector::class,
        // HamcrestToPHPUnitRector::class,
        // PHPUnitToMockeryRector::class,
        // ProphecyToMockeryRector::class,
        // ShouldReceiveToAllowsRector::class,
        // ShouldReceiveToExpectsRector::class,
        // UseMockeryPHPUnitIntegrationTraitRector::class,
    ])
    ->withSets([
         // version sets
         MockerySetList::MOCKERY_1_6, // v1.6.0
         MockerySetList::MOCKERY_2_0, // v2.0.0
         // or level sets
         MockeryLevelSetList::UP_TO_MOCKERY_1_6, // v0.1.0 - v1.6.0
         MockeryLevelSetList::UP_TO_MOCKERY_2_0, // v0.1.0 - v2.0.0
         // or migration sets
         MockerySetList::PHPUNIT_TO_MOCKERY, // PHPUnit to Mockery
         MockerySetList::PROPHECY_TO_MOCKERY, // Prophecy to Mockery
    ]);
```

### Mockery Rector Rules

To see all the rules, check the [docs/rector_rules_overview.md](./docs/rector_rules_overview.md) file.

### Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/mockery-rector/contributors)

### Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information on what has changed recently.

### License

Please see [LICENSE](./LICENSE) for more information on the license that applies to this project.

### Security

Please see [SECURITY.md](./SECURITY.md) for more information on security disclosure process.
