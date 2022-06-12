<?php

declare(strict_types=1);

namespace Bakame\Intl;

use DateTimeZone;
use IntlDateFormatter;
use Locale;

final class DateFactory
{
    /** @readonly */
    public Option\DateFormat $dateType;
    /** @readonly */
    public Option\TimeFormat $timeType;
    /* @readonly */
    public Option\CalendarFormat $calendar;
    /** @readonly */
    public ?string $pattern;
    /** @var array<IntlDateFormatter> */
    private array $dateFormatters = [];

    public function __construct(
        Option\DateFormat     $dateType,
        Option\TimeFormat     $timeType,
        Option\CalendarFormat $calendar,
        ?string               $pattern = null
    ) {
        $this->dateType = $dateType;
        $this->timeType = $timeType;
        $this->calendar = $calendar;
        $this->pattern = $pattern;
    }

    /**
     * @param array{
     *     dateFormat:'none'|'short'|'medium'|'long'|'full'|'relative_short'|'relative_medium'|'relative_long'|'relative_full',
     *     timeFormat:'none'|'short'|'medium'|'long'|'full',
     *     calendar:'gregorian'|'traditional',
     *     pattern?:?string,
     * } $settings
     */
    public static function fromAssociative(array $settings): self
    {
        if (!array_key_exists('pattern', $settings)) {
            $settings['pattern'] = null;
        }

        return new self(
            Option\DateFormat::from($settings['dateFormat']),
            Option\TimeFormat::from($settings['timeFormat']),
            Option\CalendarFormat::from($settings['calendar']),
            $settings['pattern']
        );
    }

    /**
     * @param 'none'|'short'|'medium'|'long'|'full'|'relative_short'|'relative_medium'|'relative_long'|'relative_full'|null $dateFormat
     * @param 'none'|'short'|'medium'|'long'|'full'|null $timeFormat
     * @param 'traditional'|'gregorian'|null $calendar
     */
    public function createDateFormatter(
        DateTimeZone $timezone,
        ?string $locale = null,
        ?string $dateFormat = null,
        ?string $timeFormat = null,
        ?string $pattern = null,
        ?string $calendar = null
    ): IntlDateFormatter {
        $dateType = null !== $dateFormat ? Option\DateFormat::from($dateFormat) : $this->dateType;
        $timeType = null !== $timeFormat ? Option\TimeFormat::from($timeFormat) : $this->timeType;
        $locale = $locale ?? Locale::getDefault();
        $calendar = null !== $calendar ? Option\CalendarFormat::from($calendar) : $this->calendar;
        $pattern = $pattern ?? $this->pattern;

        $hash = $locale.'|'.$dateType->value.'|'.$timeType->value.'|'.$timezone->getName().'|'.$calendar->value.'|'.$pattern;
        if (!isset($this->dateFormatters[$hash])) {
            $dateFormatter = new IntlDateFormatter($locale, $dateType->toIntlConstant(), $timeType->toIntlConstant(), $timezone, $calendar->toIntlConstant());
            if (null !== $pattern) {
                $dateFormatter->setPattern($pattern);
            }
            $this->dateFormatters[$hash] = $dateFormatter;
        }

        return $this->dateFormatters[$hash];
    }
}
