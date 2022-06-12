<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

final class SymbolOption
{
    /** @readonly */
    public SymbolFormat $name;

    /** @readonly */
    public string $value;

    private function __construct(SymbolFormat $name, string $value)
    {
        $this->value = $value;
        $this->name = $name;
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
