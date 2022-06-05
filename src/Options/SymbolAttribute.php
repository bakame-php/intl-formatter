<?php

declare(strict_types=1);

namespace Bakame\Intl\Options;

use Bakame\Intl\FailedFormatting;
use NumberFormatter;

final class SymbolAttribute
{
    private const ATTRIBUTES = [
        'decimal_separator' => NumberFormatter::DECIMAL_SEPARATOR_SYMBOL,
        'grouping_separator' => NumberFormatter::GROUPING_SEPARATOR_SYMBOL,
        'pattern_separator' => NumberFormatter::PATTERN_SEPARATOR_SYMBOL,
        'percent' => NumberFormatter::PERCENT_SYMBOL,
        'zero_digit' => NumberFormatter::ZERO_DIGIT_SYMBOL,
        'digit' => NumberFormatter::DIGIT_SYMBOL,
        'minus_sign' => NumberFormatter::MINUS_SIGN_SYMBOL,
        'plus_sign' => NumberFormatter::PLUS_SIGN_SYMBOL,
        'currency' => NumberFormatter::CURRENCY_SYMBOL,
        'intl_currency' => NumberFormatter::INTL_CURRENCY_SYMBOL,
        'monetary_separator' => NumberFormatter::MONETARY_SEPARATOR_SYMBOL,
        'exponential' => NumberFormatter::EXPONENTIAL_SYMBOL,
        'permill' => NumberFormatter::PERMILL_SYMBOL,
        'pad_escape' => NumberFormatter::PAD_ESCAPE_SYMBOL,
        'infinity' => NumberFormatter::INFINITY_SYMBOL,
        'nan' => NumberFormatter::NAN_SYMBOL,
        'significant_digit' => NumberFormatter::SIGNIFICANT_DIGIT_SYMBOL,
        'monetary_grouping_separator' => NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL,
    ];

    /** @readonly */
    public int $name;

    /** @readonly */
    public string $value;

    private function __construct(int $name, string $value)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public static function from(string $name, string $value): self
    {
        if (!isset(self::ATTRIBUTES[$name])) {
            throw FailedFormatting::dueToUnknownNumberFormatterAttributeName($name, self::ATTRIBUTES);
        }

        return new self(self::ATTRIBUTES[$name], $value);
    }

    public function addTo(NumberFormatter $numberFormatter): void
    {
        $numberFormatter->setSymbol($this->name, $this->value);
    }
}
