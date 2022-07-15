<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use IntlDateFormatter;

/**
 * @method static self None()
 * @method static self Short()
 * @method static self Medium()
 * @method static self Long()
 * @method static self Full()
 * @method static self RelativeShort()
 * @method static self RelativeMedium()
 * @method static self RelativeLong()
 * @method static self RelativeFull()
 */
final class DateFormat extends BackedEnumPolyfill
{
    public const None = 'none';
    public const Short = 'short';
    public const Medium = 'medium';
    public const Long = 'long';
    public const Full = 'full';
    public const RelativeShort = 'relative_short';
    public const RelativeMedium = 'relative_medium';
    public const RelativeLong = 'relative_long';
    public const RelativeFull = 'relative_full';

    protected const INTL_MAPPER = [
        self::None => IntlDateFormatter::NONE,
        self::Short => IntlDateFormatter::SHORT,
        self::Medium => IntlDateFormatter::MEDIUM,
        self::Long => IntlDateFormatter::LONG,
        self::Full => IntlDateFormatter::FULL,
        self::RelativeShort => IntlDateFormatter::RELATIVE_SHORT,
        self::RelativeMedium => IntlDateFormatter::RELATIVE_MEDIUM,
        self::RelativeLong => IntlDateFormatter::RELATIVE_LONG,
        self::RelativeFull => IntlDateFormatter::RELATIVE_FULL,
    ];

    protected static string $description = 'date format';
}
