<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

final class TextOption
{
    private function __construct(
        /** @readonly */
        public TextFormat $name,
        /** @readonly */
        public string $value
    ) {
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
