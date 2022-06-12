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
 */
final class TimeFormat extends BackedEnumPolyfill
{
    public const None = 'none';
    public const Short = 'short';
    public const Medium = 'medium';
    public const Long = 'long';
    public const Full = 'full';

    protected const INTL_MAPPER = [
        self::None => IntlDateFormatter::NONE,
        self::Short => IntlDateFormatter::SHORT,
        self::Medium => IntlDateFormatter::MEDIUM,
        self::Long => IntlDateFormatter::LONG,
        self::Full => IntlDateFormatter::FULL,
    ];

    protected static string $description = 'time format';
}
