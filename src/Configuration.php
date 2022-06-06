<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Options\Calendar;
use Bakame\Intl\Options\DateType;
use Bakame\Intl\Options\NumberAttribute;
use Bakame\Intl\Options\NumberStyle;
use Bakame\Intl\Options\SymbolAttribute;
use Bakame\Intl\Options\TextAttribute;
use Bakame\Intl\Options\TimeType;

final class Configuration
{
    /** @readonly */
    public DateType $dateType;
    /** @readonly */
    public TimeType $timeType;
    /** @readonly */
    public NumberStyle $style;
    /* @readonly */
    public Calendar $calendar;
    /** @readonly */
    public ?string $datePattern;
    /** @readonly */
    public ?string $numberPattern;
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

    /**
     * @param array<NumberAttribute> $attributes
     * @param array<TextAttribute> $textAttributes
     * @param array<SymbolAttribute> $symbolAttributes
     */
    public function __construct(
        DateType    $dateType,
        TimeType    $timeType,
        NumberStyle $style,
        ?Calendar    $calendar,
        ?string     $datePattern = null,
        ?string     $numberPattern = null,
        array       $attributes = [],
        array       $textAttributes = [],
        array       $symbolAttributes = []
    ) {
        $this->dateType = $dateType;
        $this->timeType = $timeType;
        $this->calendar = $calendar ?? Calendar::fromName('gregorian');
        $this->datePattern = $datePattern;
        $this->style = $style;
        $this->numberPattern = $numberPattern;
        $this->attributes = $attributes;
        $this->textAttributes = $textAttributes;
        $this->symbolAttributes = $symbolAttributes;
    }

    /**
     * @param array{
     *     date:array{
     *         dateFormat:string,
     *         timeFormat:string,
     *         pattern?:?string,
     *         calendar?:string,
     *     },
     *     number:array{
     *         style:string,
     *         pattern?:?string,
     *         attributes?:array<string, int|float|string>,
     *         textAttributes?:array<string,string>,
     *         symbolAttributes?:array<string,string>
     *     }
     * } $settings
     */
    public static function fromApplication(array $settings): self
    {
        if (!array_key_exists('calendar', $settings['date'])) {
            $settings['date']['calendar'] = 'gregorian';
        }

        if (!array_key_exists('pattern', $settings['date'])) {
            $settings['date']['pattern'] = null;
        }

        if (!array_key_exists('pattern', $settings['number'])) {
            $settings['number']['pattern'] = null;
        }

        if (!array_key_exists('attributes', $settings['number'])) {
            $settings['number']['attributes'] = [];
        }

        if (!array_key_exists('textAttributes', $settings['number'])) {
            $settings['number']['textAttributes'] = [];
        }

        if (!array_key_exists('symbolAttributes', $settings['number'])) {
            $settings['number']['symbolAttributes'] = [];
        }

        return new self(
            DateType::fromName($settings['date']['dateFormat']),
            TimeType::fromName($settings['date']['timeFormat']),
            NumberStyle::fromName($settings['number']['style']),
            Calendar::fromName($settings['date']['calendar']),
            $settings['date']['pattern'],
            $settings['number']['pattern'],
            self::filterNumberAttributes($settings['number']['attributes']),
            self::filterTextAttributes($settings['number']['textAttributes']),
            self::filterSymboAttributes($settings['number']['symbolAttributes']),
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
    private static function filterSymboAttributes(array $attributes): array
    {
        $res = [];
        foreach ($attributes as $name => $value) {
            $res[] = SymbolAttribute::from($name, $value);
        }

        return $res;
    }
}
