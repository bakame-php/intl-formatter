<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use Bakame\Intl\FailedFormatting;
use NumberFormatter;

final class AttributeOption
{
    private function __construct(
        /** @readonly */
        public AttributeFormat $name,
        /** @readonly */
        public int|float $value
    ) {
    }

    public static function from(string $name, int|float|string $value): self
    {
        $attributeName = AttributeFormat::from($name);

        return match (true) {
            !is_string($value) => new self($attributeName, $value),
            NumberFormatter::ROUNDING_MODE === $attributeName->toIntlConstant() => new self($attributeName, RoundingMode::from($value)->toIntlConstant()),
            NumberFormatter::PADDING_POSITION === $attributeName->toIntlConstant() => new self($attributeName, PaddingPosition::from($value)->toIntlConstant()),
            default => throw FailedFormatting::dueToInvalidNumberFormatterAttributeValue($name, $value),
        };
    }

    public function addTo(NumberFormatter $numberFormatter): void
    {
        $numberFormatter->setAttribute($this->name->toIntlConstant(), $this->value);
    }
}
