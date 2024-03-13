# Mockery Rector

[![Compliance](https://github.com/ghostwriter/mockery-rector/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/mockery-rector/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/mockery-rector?color=8892bf)](https://www.php.net/supported-versions)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/mockery-rector&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Code Coverage](https://codecov.io/gh/ghostwriter/mockery-rector/branch/main/graph/badge.svg)](https://codecov.io/gh/ghostwriter/mockery-rector)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/mockery-rector/coverage.svg)](https://shepherd.dev/github/ghostwriter/mockery-rector)
[![Psalm Level](https://shepherd.dev/github/ghostwriter/mockery-rector/level.svg)](https://psalm.dev/docs/running_psalm/error_levels)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/mockery-rector)](https://packagist.org/packages/ghostwriter/mockery-rector)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/mockery-rector?color=blue)](https://packagist.org/packages/ghostwriter/mockery-rector)

Rector upgrades rules for Mockery

### Star ⭐️ this repo if you find it useful

You can also star (🌟) this repo to find it easier later.

## Installation

You can install the package via composer:

``` bash
composer require rector/rector --dev
composer require ghostwriter/mockery-rector --dev
```

## Usage

To add a set to your config, use `Ghostwriter\MockeryRector\Set\ValueObject\MockeryRectorSetList` class and pick one of constants:

```php
use Ghostwriter\MockeryRector\Set\ValueObject\MockeryRectorSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withSets([
    MockeryRectorSetList::PHPUNIT_TO_MOCKERY,
    MockeryRectorSetList::PROPHECY_TO_MOCKERY,
    MockeryRectorSetList::UPDATE_V1_6_X,
    MockeryRectorSetList::UPDATE_V2_0_X,
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
