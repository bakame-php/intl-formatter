# Changelog

All Notable changes to `bakame/intl-formatter` will be documented in this file

## [0.3.0] - 2022-06-12

### Added

- None

### Fixed

- Internal Option rewritten to use pseudo Enum classes.
- `BackedEnumPolyfill` replaces `PseudoEnum` as the internal Enumeration abstract class.
- Improve docblock using phpstan annotation.

### Deprecated

- None

### Removed

- None

## [0.2.0] - 2022-06-06

### Added

- `NumberFactory` to ease `NumberFormatter` instance creation
- `DateFactory` to ease `IntlDateFormatter` instance creation
- `Options\Calendar` to ease `IntlDateFormatter` instance creation

### Fixed

- **[BC BREAK]** `Formatter` method name signature fixed by moving the `$locale` and the `$timezone` arguments
- **[BC BREAK]** `DateResolver` interface now is a final class.

### Deprecated

- None

### Removed

- **[BC BREAK]** `Configuration` class removed and split into the `NumberFactory` and the `DateFactory`

## [0.1.0] - 2022-06-05

**Initial release!**

[Next]: https://github.com/bakame-php/intl-formatter/compare/0.3.0...main
[0.3.0]: https://github.com/bakame-php/intl-formatter/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/bakame-php/intl-formatter/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/bakame-php/intl-formatter/releases/tag/0.1.0
