<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Options\Calendar;
use Bakame\Intl\Options\DateType;
use Bakame\Intl\Options\TimeType;

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
}
