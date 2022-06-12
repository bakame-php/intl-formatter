<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

/**
 * @method static self Currency()
 * @method static self Decimal()
 * @method static self Duration()
 * @method static self Ordinal()
 * @method static self Percent()
 * @method static self Scientific()
 * @method static self Spellout()
 */
final class StyleFormat extends BackedEnumPolyfill
{
    public const Currency = 'currency';
    public const Decimal = 'decimal';
    public const Duration = 'duration';
    public const Ordinal = 'ordinal';
    public const Percent = 'percent';
    public const Scientific = 'scientific';
    public const Spellout = 'spellout';

    protected const INTL_MAPPER = [
        self::Currency => NumberFormatter::CURRENCY,
        self::Decimal => NumberFormatter::DECIMAL,
        self::Duration => NumberFormatter::DURATION,
        self::Ordinal => NumberFormatter::ORDINAL,
        self::Percent => NumberFormatter::PERCENT,
        self::Scientific => NumberFormatter::SCIENTIFIC,
        self::Spellout => NumberFormatter::SPELLOUT,
    ];

    protected static string $description = 'style';
}
