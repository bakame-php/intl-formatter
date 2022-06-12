<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use IntlDateFormatter;

/**
 * @method static self Gregorian()
 * @method static self Traditional()
 */
final class CalendarFormat extends BackedEnumPolyfill
{
    public const Gregorian = 'gregorian';
    public const Traditional = 'traditional';

    protected const INTL_MAPPER = [
        self::Gregorian => IntlDateFormatter::GREGORIAN,
        self::Traditional => IntlDateFormatter::TRADITIONAL,
    ];

    protected static string $description = 'calendar name';
}
