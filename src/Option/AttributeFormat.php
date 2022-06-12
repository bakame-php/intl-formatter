<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

/**
 * @method static self GroupingUsed()
 * @method static self DecimalAlwaysShown()
 * @method static self MaxIntegerDigit()
 * @method static self MinIntegerDigit()
 * @method static self IntegerDigit()
 * @method static self MaxFractionDigit()
 * @method static self MinFractionDigit()
 * @method static self FractionDigit()
 * @method static self Multiplier()
 * @method static self GroupingSize()
 * @method static self RoundingMode()
 * @method static self RoundingIncrement()
 * @method static self FormatWidth()
 * @method static self PaddingPosition()
 * @method static self SecondaryGroupingSize()
 * @method static self SignificantDigitsUsed()
 * @method static self MinSignificantDigitsUsed()
 * @method static self MaxSignificantDigitsUsed()
 * @method static self LenientParse()
 */
final class AttributeFormat extends BackedEnumPolyfill
{
    public const GroupingUsed = 'grouping_used';
    public const DecimalAlwaysShown = 'decimal_always_shown';
    public const MaxIntegerDigit = 'max_integer_digit';
    public const MinIntegerDigit = 'min_integer_digit';
    public const IntegerDigit = 'integer_digit';
    public const MaxFractionDigit = 'max_fraction_digit';
    public const MinFractionDigit = 'min_fraction_digit';
    public const FractionDigit = 'fraction_digit';
    public const Multiplier = 'multiplier';
    public const GroupingSize = 'grouping_size';
    public const RoundingMode = 'rounding_mode';
    public const RoundingIncrement = 'rounding_increment';
    public const FormatWidth = 'format_width';
    public const PaddingPosition = 'padding_position';
    public const SecondaryGroupingSize = 'secondary_grouping_size';
    public const SignificantDigitsUsed = 'significant_digits_used';
    public const MinSignificantDigitsUsed = 'min_significant_digits_used';
    public const MaxSignificantDigitsUsed = 'max_significant_digits_used';
    public const LenientParse = 'lenient_parse';

    protected const INTL_MAPPER = [
        self::GroupingUsed => NumberFormatter::GROUPING_USED,
        self::DecimalAlwaysShown => NumberFormatter::DECIMAL_ALWAYS_SHOWN,
        self::MaxIntegerDigit => NumberFormatter::MAX_INTEGER_DIGITS,
        self::MinIntegerDigit => NumberFormatter::MIN_INTEGER_DIGITS,
        self::IntegerDigit => NumberFormatter::INTEGER_DIGITS,
        self::MaxFractionDigit => NumberFormatter::MAX_FRACTION_DIGITS,
        self::MinFractionDigit => NumberFormatter::MIN_FRACTION_DIGITS,
        self::FractionDigit => NumberFormatter::FRACTION_DIGITS,
        self::Multiplier => NumberFormatter::MULTIPLIER,
        self::GroupingSize=> NumberFormatter::GROUPING_SIZE,
        self::RoundingMode => NumberFormatter::ROUNDING_MODE,
        self::RoundingIncrement => NumberFormatter::ROUNDING_INCREMENT,
        self::FormatWidth => NumberFormatter::FORMAT_WIDTH,
        self::PaddingPosition => NumberFormatter::PADDING_POSITION,
        self::SecondaryGroupingSize => NumberFormatter::SECONDARY_GROUPING_SIZE,
        self::SignificantDigitsUsed => NumberFormatter::SIGNIFICANT_DIGITS_USED,
        self::MinSignificantDigitsUsed => NumberFormatter::MIN_SIGNIFICANT_DIGITS,
        self::MaxSignificantDigitsUsed => NumberFormatter::MAX_SIGNIFICANT_DIGITS,
        self::LenientParse => NumberFormatter::LENIENT_PARSE,
    ];

    protected static string $description = 'attribute name format';
}
