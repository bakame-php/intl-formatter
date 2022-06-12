<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

/**
 * @method static self DecimalSeparator()
 * @method static self GroupingSeparator()
 * @method static self PatternSeparator()
 * @method static self Percent()
 * @method static self ZeroDigit()
 * @method static self Digit()
 * @method static self MinusSign()
 * @method static self PlusSign()
 * @method static self Currency()
 * @method static self IntCurrency()
 * @method static self MonetarySeparator()
 * @method static self Exponential()
 * @method static self Permill()
 * @method static self PadEscape()
 * @method static self Infinity()
 * @method static self Nan()
 * @method static self SignificantDigit()
 * @method static self MonetaryGroupingSeparator()
 */
final class SymbolFormat extends BackedEnumPolyfill
{
    public const DecimalSeparator = 'decimal_separator';
    public const GroupingSeparator = 'grouping_separator';
    public const PatternSeparator = 'pattern_separator';
    public const Percent = 'percent';
    public const ZeroDigit = 'zero_digit';
    public const Digit ='digit';
    public const MinusSign = 'minus_sign';
    public const PlusSign = 'plus_sign';
    public const Currency = 'currency';
    public const IntlCurrency = 'int_currency';
    public const MonetarySeparator = 'monetary_separator';
    public const Exponential = 'exponential';
    public const Permill = 'permill';
    public const PadEscape = 'pad_escape';
    public const Infinity = 'infinity';
    public const Nan = 'nan';
    public const SignificantDigit = 'significant_digit';
    public const MonetaryGroupingSeparator = 'monetary_grouping_separator';

    protected const INTL_MAPPER = [
        self::DecimalSeparator => NumberFormatter::DECIMAL_SEPARATOR_SYMBOL,
        self::GroupingSeparator => NumberFormatter::GROUPING_SEPARATOR_SYMBOL,
        self::PatternSeparator => NumberFormatter::PATTERN_SEPARATOR_SYMBOL,
        self::Percent => NumberFormatter::PERCENT_SYMBOL,
        self::ZeroDigit => NumberFormatter::ZERO_DIGIT_SYMBOL,
        self::Digit=> NumberFormatter::DIGIT_SYMBOL,
        self::MinusSign => NumberFormatter::MINUS_SIGN_SYMBOL,
        self::PlusSign => NumberFormatter::PLUS_SIGN_SYMBOL,
        self::Currency => NumberFormatter::CURRENCY_SYMBOL,
        self::IntlCurrency => NumberFormatter::INTL_CURRENCY_SYMBOL,
        self::MonetarySeparator => NumberFormatter::MONETARY_SEPARATOR_SYMBOL,
        self::Exponential => NumberFormatter::EXPONENTIAL_SYMBOL,
        self::Permill => NumberFormatter::PERMILL_SYMBOL,
        self::PadEscape => NumberFormatter::PAD_ESCAPE_SYMBOL,
        self::Infinity => NumberFormatter::INFINITY_SYMBOL,
        self::Nan => NumberFormatter::NAN_SYMBOL,
        self::SignificantDigit => NumberFormatter::SIGNIFICANT_DIGIT_SYMBOL,
        self::MonetaryGroupingSeparator => NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL,
    ];

    protected static string $description = 'text format';
}
