<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Options\Calendar;
use Bakame\Intl\Options\DateType;
use Bakame\Intl\Options\TimeType;
use DateTimeZone;
use IntlDateFormatter;
use Locale;

final class DateFactory
{
    /** @readonly */
    public DateType $dateType;
    /** @readonly */
    public TimeType $timeType;
    /* @readonly */
    public Calendar $calendar;
    /** @readonly */
    public ?string $pattern;
    /** @var array<IntlDateFormatter> */
    private array $dateFormatters = [];

    public function __construct(
        DateType $dateType,
        TimeType $timeType,
        Calendar $calendar,
        ?string $pattern
    ) {
        $this->dateType = $dateType;
        $this->timeType = $timeType;
        $this->calendar = $calendar;
        $this->pattern = $pattern;
    }

    /**
     * @param array{
     *     dateFormat:string,
     *     timeFormat:string,
     *     calendar:string,
     *     pattern?:?string,
     * } $settings
     */
    public static function fromAssociative(array $settings): self
    {
        if (!array_key_exists('pattern', $settings)) {
            $settings['pattern'] = null;
        }

        return new self(
            DateType::fromName($settings['dateFormat']),
            TimeType::fromName($settings['timeFormat']),
            Calendar::fromName($settings['calendar']),
            $settings['pattern']
        );
    }

    public function createDateFormatter(
        DateTimeZone $timezone,
        ?string $locale,
        ?string $dateFormat,
        ?string $timeFormat,
        ?string $pattern,
        ?string $calendar
    ): IntlDateFormatter {
        $dateType = null !== $dateFormat ? DateType::fromName($dateFormat) : $this->dateType;
        $timeType = null !== $timeFormat ? TimeType::fromName($timeFormat) : $this->timeType;
        $locale = $locale ?? Locale::getDefault();
        $calendar = null !== $calendar ? Calendar::fromName($calendar) : $this->calendar;
        $pattern = $pattern ?? $this->pattern;

        $hash = $locale.'|'.$dateType->value.'|'.$timeType->value.'|'.$timezone->getName().'|'.$calendar->value.'|'.$pattern;
        if (!isset($this->dateFormatters[$hash])) {
            $dateFormatter = new IntlDateFormatter($locale, $dateType->value, $timeType->value, $timezone, $calendar->value);
            if (null !== $pattern) {
                $dateFormatter->setPattern($pattern);
            }
            $this->dateFormatters[$hash] = $dateFormatter;
        }

        return $this->dateFormatters[$hash];
    }
}
