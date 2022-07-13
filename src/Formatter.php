<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Option\CalendarFormat;
use Bakame\Intl\Option\DateFormat;
use Bakame\Intl\Option\StyleFormat;
use Bakame\Intl\Option\TimeFormat;
use Bakame\Intl\Option\TypeFormat;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Intl\Locales;
use Symfony\Component\Intl\Timezones;

final class Formatter
{
    public function __construct(
        private DateFactory $dateFactory,
        private NumberFactory $numberFactory,
        private DateResolver $dateResolver
    ) {
    }

    public function getCountryName(?string $country, string $locale = null): string
    {
        if (null === $country) {
            return '';
        }

        try {
            return Countries::getName($country, $locale);
        } catch (MissingResourceException) {
            return $country;
        }
    }

    public function getCurrencyName(?string $currency, string $locale = null): string
    {
        if (null === $currency) {
            return '';
        }

        try {
            return Currencies::getName($currency, $locale);
        } catch (MissingResourceException) {
            return $currency;
        }
    }

    public function getCurrencySymbol(?string $currency, string $locale = null): string
    {
        if (null === $currency) {
            return '';
        }

        try {
            return Currencies::getSymbol($currency, $locale);
        } catch (MissingResourceException) {
            return $currency;
        }
    }

    public function getLanguageName(?string $language, string $locale = null): string
    {
        if (null === $language) {
            return '';
        }

        try {
            return Languages::getName($language, $locale);
        } catch (MissingResourceException) {
            return $language;
        }
    }

    public function getLocaleName(?string $data, string $locale = null): string
    {
        if (null === $data) {
            return '';
        }

        try {
            return Locales::getName($data, $locale);
        } catch (MissingResourceException) {
            return $data;
        }
    }

    public function getTimezoneName(?string $timezone, string $locale = null): string
    {
        if (null === $timezone) {
            return '';
        }

        try {
            return Timezones::getName($timezone, $locale);
        } catch (MissingResourceException) {
            return $timezone;
        }
    }

    /**
     * @return array<string>
     */
    public function getCountryTimezones(string $country): array
    {
        try {
            return Timezones::forCountryCode($country);
        } catch (MissingResourceException) {
            return [];
        }
    }

    /**
     * @param array<string, int|float|string> $attrs
     */
    public function formatCurrency(int|float $amount, string $currency, ?string $locale = null, array $attrs = []): string
    {
        $formatter = $this->numberFactory->createNumberFormatter($locale, StyleFormat::Currency(), $attrs);
        if (false === $ret = $formatter->formatCurrency($amount, $currency)) {
            // @codeCoverageIgnoreStart
            throw FailedFormatting::dueToNumberFormatter('Unable to format the given number as a currency.');
            // @codeCoverageIgnoreEnd
        }

        return $ret;
    }

    /**
     * @param Option\TypeFormat|key-of<Option\TypeFormat::INTL_MAPPER> $type
     * @param array<string, int|float|string> $attrs
     * @param Option\StyleFormat|key-of<Option\StyleFormat::INTL_MAPPER>|null $style
     */
    public function formatNumber(
        int|float $number,
        ?string $locale = null,
        TypeFormat|string $type = 'default',
        array $attrs = [],
        StyleFormat|string|null $style = null
    ): string {
        $formatter = $this->numberFactory->createNumberFormatter($locale, $style, $attrs);
        if (!$type instanceof TypeFormat) {
            $type = TypeFormat::from($type);
        }

        if (false === $ret = $formatter->format($number, $type->toIntlConstant())) {
            // @codeCoverageIgnoreStart
            throw FailedFormatting::dueToNumberFormatter('Unable to format the given number.');
            // @codeCoverageIgnoreEnd
        }

        return $ret;
    }

    /**
     * @param DateFormat|key-of<DateFormat::INTL_MAPPER>|null $dateFormat
     * @param TimeFormat|key-of<TimeFormat::INTL_MAPPER>|null $timeFormat
     * @param CalendarFormat|key-of<CalendarFormat::INTL_MAPPER>|null $calendar
     *
     * @throws FailedFormatting
     */
    public function formatDateTime(
        DateTimeInterface|string|int|null $date,
        ?string $locale = null,
        DateTimeZone|string|false|null $timezone = null,
        DateFormat|string|null $dateFormat = null,
        TimeFormat|string|null $timeFormat = null,
        ?string $pattern = null,
        CalendarFormat|string|null $calendar = null
    ): string {
        try {
            $date = $this->dateResolver->resolve($date, $timezone);
        } catch (Exception $exception) {
            throw FailedFormatting::dueToInvalidDate($exception);
        }

        $formatter = $this->dateFactory->createDateFormatter($date->getTimezone(), $locale, $dateFormat, $timeFormat, $pattern, $calendar);
        if (false === $ret = $formatter->format($date)) {
            // @codeCoverageIgnoreStart
            throw FailedFormatting::dueToDateFormatter('Unable to format the given date.');
            // @codeCoverageIgnoreEnd
        }

        return $ret;
    }

    /**
     * @param DateFormat|key-of<DateFormat::INTL_MAPPER>|null $dateFormat
     * @param CalendarFormat|key-of<CalendarFormat::INTL_MAPPER>|null $calendar
     */
    public function formatDate(
        DateTimeInterface|string|int|null $date,
        ?string $locale = null,
        DateTimeZone|string|false|null $timezone = null,
        DateFormat|string|null $dateFormat = null,
        ?string $pattern = null,
        CalendarFormat|string|null $calendar = null
    ): string {
        return $this->formatDateTime($date, $locale, $timezone, $dateFormat, TimeFormat::None(), $pattern, $calendar);
    }

    /**
     * @param TimeFormat|key-of<TimeFormat::INTL_MAPPER>|null $timeFormat
     * @param CalendarFormat|key-of<CalendarFormat::INTL_MAPPER>|null $calendar
     */
    public function formatTime(
        DateTimeInterface|string|int|null $date,
        ?string $locale = null,
        DateTimeZone|string|false|null $timezone = null,
        TimeFormat|string|null $timeFormat = null,
        ?string $pattern = null,
        CalendarFormat|string|null $calendar = null
    ): string {
        return $this->formatDateTime($date, $locale, $timezone, DateFormat::None(), $timeFormat, $pattern, $calendar);
    }
}
