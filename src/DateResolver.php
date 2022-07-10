<?php

declare(strict_types=1);

namespace Bakame\Intl;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;

final class DateResolver
{
    private function __construct(
        private DateTimeZone $timezone
    ) {
    }

    public static function fromSystem(): self
    {
        return self::fromTimeZoneIdentifier(date_default_timezone_get());
    }

    public static function fromTimeZoneIdentifier(string $identifier): self
    {
        return new self(new DateTimeZone($identifier));
    }

    public static function fromTimeZone(DateTimeZone $timezone): self
    {
        return new self($timezone);
    }

    /**
     * @param DateTimeInterface|string|int|null $date A date or null to use the current time
     * @param DateTimeZone|string|false|null $timezone The target timezone, null to use the default, false to leave unchanged
     *
     * @throws Exception
     */
    public function resolve($date, $timezone): DateTimeInterface
    {
        $timezone = $this->determineTheTimezone($timezone);
        if ($date instanceof DateTimeImmutable) {
            return null !== $timezone ? $date->setTimezone($timezone) : $date;
        }

        if ($date instanceof DateTime) {
            $date = DateTimeImmutable::createFromMutable($date);

            return null !== $timezone ? $date->setTimezone($timezone) : $date;
        }

        $asString = (string) $date;
        if (null === $date || 'now' === strtolower($asString)) {
            return new DateTimeImmutable('now', $timezone);
        }

        if (1 === preg_match('/^-?\d+$/', $asString)) {
            $date = new DateTimeImmutable('@'.$asString);

            return null !== $timezone ? $date->setTimezone($timezone) : $date;
        }

        return new DateTimeImmutable($asString, $timezone);
    }

    /**
     * @param DateTimeZone|string|false|null $timezone The target timezone, null to use the default, false to leave unchanged
     */
    private function determineTheTimezone($timezone): ?DateTimeZone
    {
        return match (true) {
            null === $timezone => $this->timezone,
            false === $timezone => null,
            $timezone instanceof DateTimeZone => $timezone,
            default => new DateTimeZone($timezone),
        };
    }
}
