<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Options\NumberAttribute;
use Bakame\Intl\Options\NumberStyle;
use Bakame\Intl\Options\SymbolAttribute;
use Bakame\Intl\Options\TextAttribute;
use Locale;
use NumberFormatter;

final class NumberFactory
{
    /** @readonly */
    public NumberStyle $style;
    /** @readonly */
    public ?string $pattern;
    /**
     * @readonly
     * @var array<NumberAttribute>
     */
    public array $attributes;
    /**
     * @readonly
     * @var array<TextAttribute>
     */
    public array $textAttributes;
    /**
     * @readonly
     * @var array<SymbolAttribute>
     */
    public array $symbolAttributes;

    /** @var array<NumberFormatter> */
    private array $numberFormatters = [];

    /**
     * @param array<NumberAttribute> $attributes
     * @param array<TextAttribute> $textAttributes
     * @param array<SymbolAttribute> $symbolAttributes
     */
    public function __construct(
        NumberStyle $style,
        ?string     $pattern = null,
        array       $attributes = [],
        array       $textAttributes = [],
        array       $symbolAttributes = []
    ) {
        $this->style = $style;
        $this->pattern = $pattern;
        $this->attributes = $attributes;
        $this->textAttributes = $textAttributes;
        $this->symbolAttributes = $symbolAttributes;
    }

    /**
     * @param array{
     *         style:string,
     *         pattern?:?string,
     *         attributes?:array<string, int|float|string>,
     *         textAttributes?:array<string,string>,
     *         symbolAttributes?:array<string,string>
     * } $settings
     */
    public static function fromAssociative(array $settings): self
    {
        if (!array_key_exists('pattern', $settings)) {
            $settings['pattern'] = null;
        }

        if (!array_key_exists('attributes', $settings)) {
            $settings['attributes'] = [];
        }

        if (!array_key_exists('textAttributes', $settings)) {
            $settings['textAttributes'] = [];
        }

        if (!array_key_exists('symbolAttributes', $settings)) {
            $settings['symbolAttributes'] = [];
        }

        return new self(
            NumberStyle::fromName($settings['style']),
            $settings['pattern'],
            self::filterNumberAttributes($settings['attributes']),
            self::filterTextAttributes($settings['textAttributes']),
            self::filterSymbolAttributes($settings['symbolAttributes']),
        );
    }

    /**
     * @param array<string,int|float|string> $attributes
     *
     * @return array<NumberAttribute>
     */
    private static function filterNumberAttributes(array $attributes): array
    {
        $res = [];
        foreach ($attributes as $name => $value) {
            $res[] = NumberAttribute::from($name, $value);
        }

        return $res;
    }

    /**
     * @param array<string,string> $attributes
     *
     * @return array<TextAttribute>
     */
    private static function filterTextAttributes(array $attributes): array
    {
        $res = [];
        foreach ($attributes as $name => $value) {
            $res[] = TextAttribute::from($name, $value);
        }

        return $res;
    }

    /**
     * @param array<string,string> $attributes
     *
     * @return array<SymbolAttribute>
     */
    private static function filterSymbolAttributes(array $attributes): array
    {
        $res = [];
        foreach ($attributes as $name => $value) {
            $res[] = SymbolAttribute::from($name, $value);
        }

        return $res;
    }

    /**
     * @param array<string, string|float|int> $attrs
     */
    public function createNumberFormatter(?string $locale, ?string $style = null, array $attrs = []): NumberFormatter
    {
        $locale = $locale ?? Locale::getDefault();
        $style = null !== $style ? NumberStyle::fromName($style) : $this->style;
        ksort($attrs);
        $hash = $locale.'|'.$style->value.'|'.json_encode($attrs);
        if (!isset($this->numberFormatters[$hash])) {
            $this->numberFormatters[$hash] = $this->newNumberFormatter($locale, $style, $attrs);
        }

        return $this->numberFormatters[$hash];
    }

    /**
     * Returns a new NumberFormatter.
     *
     * @param array<string, int|float|string> $extraAttributes
     */
    private function newNumberFormatter(
        string $locale,
        NumberStyle $style,
        array $extraAttributes = []
    ): NumberFormatter {
        $numberFormatter = new NumberFormatter($locale, $style->value);

        foreach ([
            ...$this->attributes,
            ...self::filterNumberAttributes($extraAttributes),
            ...$this->textAttributes,
            ...$this->symbolAttributes,
            ] as $attribute) {
            $attribute->addTo($numberFormatter);
        }

        if (null !== $this->pattern) {
            $numberFormatter->setPattern($this->pattern);
        }

        return $numberFormatter;
    }
}
