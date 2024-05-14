# Hybrid PHPCS ruleset for OOP WordPress development

[![Build Status](https://app.travis-ci.com/szepeviktor/phpcs-psr-12-neutron-hybrid-ruleset.svg?token=CgYVxsSdNVnRNCDNV3qG&branch=master)](https://app.travis-ci.com/szepeviktor/phpcs-psr-12-neutron-hybrid-ruleset)
[![Packagist Version](https://img.shields.io/packagist/v/szepeviktor/phpcs-psr-12-neutron-hybrid-ruleset)](https://packagist.org/packages/szepeviktor/phpcs-psr-12-neutron-hybrid-ruleset)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/szepeviktor/phpcs-psr-12-neutron-hybrid-ruleset)](https://packagist.org/packages/szepeviktor/phpcs-psr-12-neutron-hybrid-ruleset)

- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PSR-12](https://www.php-fig.org/psr/psr-12/)
- [Neutron PHP Ruleset by Automattic](https://github.com/Automattic/phpcs-neutron-ruleset)
- [Slevomat Coding Standard](https://github.com/slevomat/coding-standard)

### Features

- PSR-12 Extended Coding Style as starting point (formatting)
- All [WPCS](https://github.com/WordPress/WordPress-Coding-Standards) features through Neutron (except formatting)
- File permission bits
- Strict types
- File, class and method comments
- Handpicked [Slevomat](https://github.com/slevomat/coding-standard) rules

### Usage

```bash
composer require --dev szepeviktor/phpcs-psr-12-neutron-hybrid-ruleset

./vendor/bin/phpcs --standard=PSR12NeutronRuleset src/
```

### About the `@package` tag

- The origins of the `@package` tag are in [PEAR](https://pear.php.net/manual/en/standards.header.php)
  where packages are called for example `Net_Ping`
- You can put your Composer package name there: `yoast/phpunit-polyfills`
- Or you can use your WordPress.org plugin slug: `wordpress-seo`
