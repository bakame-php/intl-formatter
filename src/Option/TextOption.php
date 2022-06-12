<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

final class TextOption
{
    /** @readonly */
    public string $value;

    /** @readonly */
    public TextFormat $name;

    private function __construct(TextFormat $name, string $value)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public static function from(string $name, string $value): self
    {
        return new self(TextFormat::from($name), $value);
    }

    public function addTo(NumberFormatter $numberFormatter): void
    {
        $numberFormatter->setTextAttribute($this->name->toIntlConstant(), $this->value);
    }
}
