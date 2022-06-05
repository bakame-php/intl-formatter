<?php

declare(strict_types=1);

namespace Bakame\Intl\Options;

use Bakame\Intl\FailedFormatting;
use NumberFormatter;

final class TextAttribute
{
    private const ATTRIBUTES = [
        'positive_prefix' => NumberFormatter::POSITIVE_PREFIX,
        'positive_suffix' => NumberFormatter::POSITIVE_SUFFIX,
        'negative_prefix' => NumberFormatter::NEGATIVE_PREFIX,
        'negative_suffix' => NumberFormatter::NEGATIVE_SUFFIX,
        'padding_character' => NumberFormatter::PADDING_CHARACTER,
        'currency_code' => NumberFormatter::CURRENCY_CODE,
        'default_ruleset' => NumberFormatter::DEFAULT_RULESET,
        'public_rulesets' => NumberFormatter::PUBLIC_RULESETS,
    ];

    /** @readonly */
    public string $value;

    /** @readonly */
    public int $name;

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
        $numberFormatter->setTextAttribute($this->name, $this->value);
    }
}
