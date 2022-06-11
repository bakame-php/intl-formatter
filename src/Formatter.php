<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Options\NumberType;
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
    private DateFactory $dateFactory;
    private NumberFactory $numberFactory;
    private DateResolver $dateResolver;

    public function __construct(
        DateFactory $dateFactory,
        NumberFactory $numberFactory,
        DateResolver $dateResolver
    ) {
        $this->dateFactory = $dateFactory;
        $this->numberFactory = $numberFactory;
        $this->dateResolver = $dateResolver;
    }

    public function getCountryName(?string $country, string $locale = null): string
    {
        if (null === $country) {
            return '';
        }

        try {
            return Countries::getName($country, $locale);
        } catch (MissingResourceException $exception) {
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
        } catch (MissingResourceException $exception) {
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
        } catch (MissingResourceException $exception) {
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
        } catch (MissingResourceException $exception) {
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
        } catch (MissingResourceException $exception) {
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
        } catch (MissingResourceException $exception) {
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
        } catch (MissingResourceException $exception) {
            return [];
        }
    }

    /**
     * @param int|float $amount
     * @param array<string, int|float|string> $attrs
     */
    public function formatCurrency($amount, string $currency, ?string $locale = null, array $attrs = []): string
    {
        $formatter = $this->numberFactory->createNumberFormatter($locale, 'currency', $attrs);
        if (false === $ret = $formatter->formatCurrency($amount, $currency)) {
            // @codeCoverageIgnoreStart
            throw FailedFormatting::dueToNumberFormatter('Unable to format the given number as a currency.');
            // @codeCoverageIgnoreEnd
        }

        return $ret;
    }

    /**
     * @param 'default'|'int32'|'int64'|'double'|'currency' $type
     * @param int|float $number
     * @param array<string, int|float|string> $attrs
     * @param 'decimal'|'currency'|'percent'|'scientific'|'spellout'|'ordinal'|'duration'|null $style
     */
    public function formatNumber(
        $number,
        ?string $locale = null,
        string $type = 'default',
        array $attrs = [],
        ?string $style = null
    ): string {
        $formatter = $this->numberFactory->createNumberFormatter($locale, $style, $attrs);
        if (false === $ret = $formatter->format($number, NumberType::fromName($type)->value)) {
            // @codeCoverageIgnoreStart
            throw FailedFormatting::dueToNumberFormatter('Unable to format the given number.');
            // @codeCoverageIgnoreEnd
        }

        return $ret;
    }

    /**
     * @param DateTimeInterface|string|int|null $date A date or null to use the current time
     * @param DateTimeZone|string|false|null $timezone The target timezone, null to use the default, false to leave unchanged
     * @param 'none'|'short'|'medium'|'long'|'full'|'relative_short'|'relative_medium'|'relative_long'|'relative_full'|null $dateFormat
     * @param 'none'|'short'|'medium'|'long'|'full'|null $timeFormat
     * @param 'gregorian'|'traditional'|null $calendar
     *
     * @throws FailedFormatting
     */
    public function formatDateTime(
        $date,
        ?string $locale = null,
        $timezone = null,
        ?string $dateFormat = null,
        ?string $timeFormat = null,
        ?string $pattern = null,
        ?string $calendar = null
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
     * @param DateTimeInterface|string|int|null $date A date or null to use the current time
     * @param DateTimeZone|string|false|null $timezone The target timezone, null to use the default, false to leave unchanged
     * @param 'none'|'short'|'medium'|'long'|'full'|'relative_short'|'relative_medium'|'relative_long'|'relative_full'|null $dateFormat
     * @param 'gregorian'|'traditional'|null $calendar
     */
    public function formatDate(
        $date,
        ?string $locale = null,
        $timezone = null,
        ?string $dateFormat = null,
        ?string $pattern = null,
        ?string $calendar = null
    ): string {
        return $this->formatDateTime($date, $locale, $timezone, $dateFormat, 'none', $pattern, $calendar);
    }

    /**
     * @param DateTimeInterface|string|int|null $date A date or null to use the current time
     * @param DateTimeZone|string|false|null $timezone The target timezone, null to use the default, false to leave unchanged
     * @param 'none'|'short'|'medium'|'long'|'full'|null $timeFormat
     * @param 'gregorian'|'traditional'|null $calendar
     */
    public function formatTime(
        $date,
        ?string $locale = null,
        $timezone = null,
        ?string $timeFormat = null,
        ?string $pattern = null,
        ?string $calendar = null
    ): string {
        return $this->formatDateTime($date, $locale, $timezone, 'none', $timeFormat, $pattern, $calendar);
    }
}
