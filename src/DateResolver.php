<?php

declare(strict_types=1);

namespace Bakame\Intl;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Stringable;

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
        return self::fromTimeZone(new DateTimeZone($identifier));
    }

    public static function fromTimeZone(DateTimeZone $timezone): self
    {
        return new self($timezone);
    }

    /**
     * @throws Exception
     */
    public function resolve(
        DateTimeInterface|Stringable|string|int|null $date,
        DateTimeZone|string|false|null $timezone
    ): DateTimeInterface {
        $timezone = match (true) {
            null === $timezone => $this->timezone,
            false === $timezone => null,
            $timezone instanceof DateTimeZone => $timezone,
            default => new DateTimeZone($timezone),
        };

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
}
