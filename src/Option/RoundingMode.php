<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

/**
 * @method static self Ceiling()
 * @method static self Floor()
 * @method static self Down()
 * @method static self Up()
 * @method static self Halfeven()
 * @method static self Halfdown()
 * @method static self Halfup()
 */
final class RoundingMode extends BackedEnumPolyfill
{
    public const Ceiling = 'ceiling';
    public const Floor = 'floor';
    public const Down = 'down';
    public const Up = 'up';
    public const HalfEven = 'halfeven';
    public const HalfDown = 'halfdown';
    public const HalfUp = 'halfup';

    protected const INTL_MAPPER = [
        self::Ceiling => NumberFormatter::ROUND_CEILING,
        self::Floor => NumberFormatter::ROUND_FLOOR,
        self::Down => NumberFormatter::ROUND_DOWN,
        self::Up => NumberFormatter::ROUND_UP,
        self::HalfEven => NumberFormatter::ROUND_HALFEVEN,
        self::HalfDown => NumberFormatter::ROUND_HALFDOWN,
        self::HalfUp => NumberFormatter::ROUND_HALFUP,
    ];

    protected static string $description = 'rounding mode';
}
