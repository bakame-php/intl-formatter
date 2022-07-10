<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

final class SymbolOption
{
    private function __construct(
        /** @readonly */
        public SymbolFormat $name,
        /** @readonly */
        public string $value
    ) {
    }

    public static function from(string $name, string $value): self
    {
        return new self(SymbolFormat::from($name), $value);
    }

    public function addTo(NumberFormatter $numberFormatter): void
    {
        $numberFormatter->setSymbol($this->name->toIntlConstant(), $this->value);
    }
}
