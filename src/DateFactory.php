<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Option\CalendarFormat;
use Bakame\Intl\Option\DateFormat;
use Bakame\Intl\Option\TimeFormat;
use DateTimeZone;
use IntlDateFormatter;
use Locale;

final class DateFactory
{
    /** @var array<IntlDateFormatter> */
    private array $dateFormatters = [];

    public function __construct(
        /* @readonly */
        public Option\DateFormat $dateType,
        /* @readonly */
        public Option\TimeFormat $timeType,
        /* @readonly */
        public Option\CalendarFormat $calendar,
        /** @readonly */
        public ?string $pattern = null
    ) {
    }

    /**
     * @param array{
     *     dateFormat:key-of<DateFormat::INTL_MAPPER>,
     *     timeFormat:key-of<TimeFormat::INTL_MAPPER>,
     *     calendar:key-of<CalendarFormat::INTL_MAPPER>,
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
     * @param key-of<DateFormat::INTL_MAPPER>|null $dateFormat
     * @param key-of<TimeFormat::INTL_MAPPER>|null $timeFormat
     * @param key-of<CalendarFormat::INTL_MAPPER>|null $calendar
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
            $dateFormatter = new IntlDateFormatter(
                $locale,
                $dateType->toIntlConstant(),
                $timeType->toIntlConstant(),
                $timezone,
                $calendar->toIntlConstant()
            );
            if (null !== $pattern) {
                $dateFormatter->setPattern($pattern);
            }
            $this->dateFormatters[$hash] = $dateFormatter;
        }

        return $this->dateFormatters[$hash];
    }
}
