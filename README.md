Laravel Intl Formatter
=======================================

[![Author](http://img.shields.io/badge/author-@nyamsprod-blue.svg?style=flat-square)](https://twitter.com/nyamsprod)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://github.com/bakame-php/intl-formatter/workflows/build/badge.svg)](https://github.com/bakame-php/intl-formatter/actions?query=workflow%3A%22build%22)
[![Latest Version](https://img.shields.io/github/release/bakame-php/intl-formatter.svg?style=flat-square)](https://github.com/bakame-php/intl-formatter/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/bakame/intl-formatter.svg?style=flat-square)](https://packagist.org/packages/bakame/intl-formatter)
[![Sponsor development of this project](https://img.shields.io/badge/sponsor%20this%20package-%E2%9D%A4-ff69b4.svg?style=flat-square)](https://github.com/sponsors/nyamsprod)

The package can be used in any Laravel based application to quickly handle internationalization 
by providing helper functions in Blade templates or Laravel codebase.

System Requirements
-------

- PHP7.4+
- Symfony Intl component

Installation
------------

Use composer:

```
composer require bakame/intl-formatter
```

Documentation
------------

Once installed the package provides a Formatter class to help in internationalization using the Intl extension
and/or Symfony Intl package.

Contributing
-------

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE OF CONDUCT](.github/CODE_OF_CONDUCT.md) for details.

Testing
-------

The library:

- has a [PHPUnit](https://phpunit.de) test suite
- has a coding style compliance test suite using [PHP CS Fixer](https://cs.sensiolabs.org/).
- has a code analysis compliance test suite using [PHPStan](https://github.com/phpstan/phpstan).

To run the tests, run the following command from the project folder.

``` bash
$ composer test
```

Security
-------

If you discover any security related issues, please email nyamsprod@gmail.com instead of using the issue tracker.

Credits
-------

- [ignace nyamagana butera](https://github.com/nyamsprod)
- [All Contributors](https://github.com/bakame-php/intl-formatter/contributors)

Attribution
-------

The package `Formatter` class and the exposed functions are heavily inspired by previous works done by [Fabien Potencier](https://github.com/fabpot) on [Twig Intl Extension](https://github.com/twigphp/intl-extra).

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.
