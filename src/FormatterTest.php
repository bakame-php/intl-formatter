<?php

declare(strict_types=1);

namespace Bakame\Intl;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Bakame\Intl\Formatter
 */
final class FormatterTest extends TestCase
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formatter = new Formatter(
            DateFactory::fromAssociative(['dateFormat' => 'full', 'timeFormat' => 'full', 'calendar' => 'gregorian']),
            NumberFactory::fromAssociative(['style' => 'decimal']),
            DateResolver::fromSystem()
        );
    }

    /** @test */
    public function it_can_be_instantiated_with_a_different_configuration(): void
    {
        $dateFactory = DateFactory::fromAssociative([
            'dateFormat' => 'full',
            'timeFormat' => 'full',
            'calendar' => 'gregorian',
        ]);

        $numberFactory = NumberFactory::fromAssociative([
            'style' => 'decimal',
            'attributes' => ['fraction_digit' => 1],
            'textAttributes' => ['positive_prefix' => '++'],
            'symbolAttributes' => ['decimal_separator' => 'x'],
        ]);

        $formatter = new Formatter($dateFactory, $numberFactory, DateResolver::fromSystem());

        self::assertSame('++12x3', $formatter->formatNumber(12.3456, 'fr', 'default', ['padding_position' => 'after_prefix'], 'decimal'));
    }

    /** @test */
    public function it_can_handle_date_type(): void
    {
        $dateString = '2019-08-07 23:39:12';
        $dateImmutable = new DateTimeImmutable('2019-08-07 23:39:12');
        $date = new DateTime('2019-08-07 23:39:12');

        self::assertSame('Jun 3, 2022', $this->formatter->formatDate(1654247542, null, null, 'medium'));
        self::assertSame('Jun 3, 2022', $this->formatter->formatDate('1654247542', null, null, 'medium'));
        self::assertSame($this->formatter->formatDate(null), $this->formatter->formatDate('NoW'));
        self::assertSame($this->formatter->formatDate($date), $this->formatter->formatDate($dateImmutable));
        self::assertSame($this->formatter->formatDate($date), $this->formatter->formatDate($dateString));
        self::assertSame(
            $this->formatter->formatDate($dateString, null, 'Africa/Kinshasa', 'full', null),
            $this->formatter->formatDate($dateString, null, new DateTimeZone('Africa/Kinshasa'), 'full', null)
        );

        self::assertNotSame(
            $this->formatter->formatDateTime($dateString, null, 'Africa/Kinshasa', 'full', 'full', null),
            $this->formatter->formatDateTime($dateString, null, false, 'full', 'full', null)
        );
    }

    /** @test */
    public function it_fails_to_format_an_invalid_date(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatDate('foobar');
    }

    /** @test */
    public function it_fails_to_format_a_date_with_an_invalid_date_format(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatDate('2019-08-07 23:39:12', null, null, 'foobar'); /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_to_format_a_time_with_an_invalid_time_format(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatTime('2019-08-07 23:39:12', null, null, 'foobar'); /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_style(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, null, 'default', [], 'foobar'); /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_type(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, null, 'foobar', [], 'decimal'); /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_to_format_a_number_with_unknown_attributes(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, null, 'default', ['foobar' => 1]);  /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_rouding_mode_attributes(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, null, 'default', ['rounding_mode' => 'foobar']); /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_padding_position_attributes(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, null, 'default', ['padding_position' => 'foobar']); /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_attributes_value(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, null, 'default', ['grouping_used' => 'foobar']); /* @phpstan-ignore-line */
    }

    /**
     * @test
     * @dataProvider currencyNameProvider
     */
    public function it_can_get_the_currency_name(?string $currency, ?string $locale, string $expected): void
    {
        self::assertSame($expected, $this->formatter->getCurrencyName($currency, $locale));
    }

    /**
     * @return iterable<string, array{currency:?string, locale:?string, expected:string}>
     */
    public function currencyNameProvider(): iterable
    {
        yield 'unknown name' => [
            'currency' => 'UNKNOWN',
            'locale' => null,
            'expected' => 'UNKNOWN',
        ];

        yield 'null value' => [
            'currency' => null,
            'locale' => null,
            'expected' => '',
        ];

        yield 'euro with default locale' => [
            'currency' => 'EUR',
            'locale' => null,
            'expected' => 'Euro',
        ];

        yield 'yen with default locale' => [
            'currency' => 'JPY',
            'locale' => null,
            'expected' => 'Japanese Yen',
        ];

        yield 'euro with french locale' => [
            'currency' => 'EUR',
            'locale' => 'fr',
            'expected' => 'euro',
        ];

        yield 'yen with french from France locale' => [
            'currency' => 'JPY',
            'locale' => 'fr_FR',
            'expected' => 'yen japonais',
        ];
    }

    /**
     * @test
     * @dataProvider countryNameProvider
     */
    public function it_can_get_the_country_name(?string $country, ?string $locale, string $expected): void
    {
        self::assertSame($expected, $this->formatter->getCountryName($country, $locale));
    }

    /**
     * @return iterable<string, array{country:?string, locale:?string, expected:string}>
     */
    public function countryNameProvider(): iterable
    {
        yield 'unknown name' => [
            'country' => 'UNKNOWN',
            'locale' => null,
            'expected' => 'UNKNOWN',
        ];

        yield 'null value' => [
            'country' => null,
            'locale' => null,
            'expected' => '',
        ];

        yield 'France default locale' => [
            'country' => 'FR',
            'locale' => null,
            'expected' => 'France',
        ];

        yield 'US default locale' => [
            'country' => 'US',
            'locale' => null,
            'expected' => 'United States',
        ];

        yield 'US with fr locale' => [
            'country' => 'US',
            'locale' => 'fr',
            'expected' => 'États-Unis',
        ];

        yield 'Swiss with fr canada locale' => [
            'country' => 'CH',
            'locale' => 'fr_CA',
            'expected' => 'Suisse',
        ];
    }
}
