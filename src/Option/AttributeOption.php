<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use Bakame\Intl\FailedFormatting;
use NumberFormatter;

final class AttributeOption
{
    /**
     * @readonly
     *
     * @var int|float
     */
    public $value;

    /** @readonly */
    public AttributeFormat $name;

    /**
     * @param int|float $value
     */
    private function __construct(AttributeFormat $name, $value)
    {
        $this->value = $value;
        $this->name = $name;
    }

    /**
     * @param int|float|string $value
     */
    public static function from(string $name, $value): self
    {
        $attributeName = AttributeFormat::from($name);

        if (NumberFormatter::ROUNDING_MODE === $attributeName->toIntlConstant() && is_string($value)) {
            return new self($attributeName, RoundingMode::from($value)->toIntlConstant());
        }

        if (NumberFormatter::PADDING_POSITION === $attributeName->toIntlConstant() && is_string($value)) {
            return new self($attributeName, PaddingPosition::from($value)->toIntlConstant());
        }

        if (is_string($value)) {
            throw FailedFormatting::dueToInvalidNumberFormatterAttributeValue($name, $value);
        }

        return new self($attributeName, $value);
    }

    public function addTo(NumberFormatter $numberFormatter): void
    {
        $numberFormatter->setAttribute($this->name->toIntlConstant(), $this->value);
    }
}
