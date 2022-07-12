<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Option\AttributeFormat;
use Bakame\Intl\Option\PaddingPosition;
use Bakame\Intl\Option\RoundingMode;
use Bakame\Intl\Option\StyleFormat;
use Bakame\Intl\Option\SymbolFormat;
use Bakame\Intl\Option\TextFormat;
use Locale;
use NumberFormatter;

final class NumberFactory
{
    /** @var array<NumberFormatter> */
    private array $numberFormatters = [];

    /**
     * @param array<Option\AttributeOption> $attributes
     * @param array<Option\TextOption> $textAttributes
     * @param array<Option\SymbolOption> $symbolAttributes
     */
    public function __construct(
        /** @readonly */
        public Option\StyleFormat $style,
        /** @readonly */
        public ?string $pattern = null,
        /**
         * @readonly
         * @var array<Option\AttributeOption>
         */
        public array $attributes = [],
        /**
         * @readonly
         * @var array<Option\TextOption>
         */
        public array $textAttributes = [],
        /**
         * @readonly
         * @var array<Option\SymbolOption>
         */
        public array $symbolAttributes = []
    ) {
    }

    /**
     * @param array{
     *         style:key-of<StyleFormat::INTL_MAPPER>,
     *         pattern?:?string,
     *         attributes?:array<key-of<AttributeFormat::INTL_MAPPER>, int|float|key-of<RoundingMode::INTL_MAPPER>|key-of<PaddingPosition::INTL_MAPPER>>,
     *         textAttributes?:array<key-of<TextFormat::INTL_MAPPER>,string>,
     *         symbolAttributes?:array<key-of<SymbolFormat::INTL_MAPPER>,string>
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
            Option\StyleFormat::from($settings['style']),
            $settings['pattern'],
            self::filterNumberAttributes($settings['attributes']),
            self::filterTextAttributes($settings['textAttributes']),
            self::filterSymbolAttributes($settings['symbolAttributes']),
        );
    }

    /**
     * @param array<string, int|float|string> $attributes
     *
     * @return array<Option\AttributeOption>
     */
    private static function filterNumberAttributes(array $attributes): array
    {
        $res = [];
        foreach ($attributes as $name => $value) {
            $res[] = Option\AttributeOption::from($name, $value);
        }

        return $res;
    }

    /**
     * @param array<key-of<TextFormat::INTL_MAPPER>, string> $attributes
     *
     * @return array<Option\TextOption>
     */
    private static function filterTextAttributes(array $attributes): array
    {
        $res = [];
        foreach ($attributes as $name => $value) {
            $res[] = Option\TextOption::from($name, $value);
        }

        return $res;
    }

    /**
     * @param array<key-of<SymbolFormat::INTL_MAPPER>, string> $attributes
     *
     * @return array<Option\SymbolOption>
     */
    private static function filterSymbolAttributes(array $attributes): array
    {
        $res = [];
        foreach ($attributes as $name => $value) {
            $res[] = Option\SymbolOption::from($name, $value);
        }

        return $res;
    }

    /**
     * @param array<string, int|float|string> $attrs
     */
    public function createNumberFormatter(
        ?string $locale,
        Option\StyleFormat|string|null $style = null,
        array $attrs = []
    ): NumberFormatter {
        $locale = $locale ?? Locale::getDefault();
        $style = match (true) {
            null === $style => $this->style,
            is_string($style) =>  Option\StyleFormat::from($style),
            default => $style,
        };

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
        Option\StyleFormat $style,
        array $extraAttributes = []
    ): NumberFormatter {
        $numberFormatter = new NumberFormatter($locale, $style->toIntlConstant());

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
