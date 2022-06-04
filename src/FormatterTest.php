<?php

declare(strict_types=1);

namespace Bakame\Intl;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use IntlDateFormatter;
use NumberFormatter;
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
            Configuration::fromApplication([
                'date' => [
                    'dateType' => IntlDateFormatter::FULL,
                    'timeType' => IntlDateFormatter::FULL,
                ],
                'number' => [
                    'style' => NumberFormatter::DECIMAL,
                ],
            ]),
            SystemDateResolver::fromSystem()
        );
    }

    /** @test */
    public function it_can_be_instantiated_with_a_different_configuration(): void
    {
        $configuration = Configuration::fromApplication([
            'date' => [
                'dateType' => IntlDateFormatter::FULL,
                'timeType' => IntlDateFormatter::FULL,
            ],
            'number' => [
                'style' => NumberFormatter::DECIMAL,
                'attributes' => [NumberFormatter::FRACTION_DIGITS => 1],
                'textAttributes' => [NumberFormatter::POSITIVE_PREFIX => '++'],
                'symbolAttributes' => [NumberFormatter::DECIMAL_SEPARATOR_SYMBOL => 'x'],
            ],
        ]);

        $formatter = new Formatter($configuration, SystemDateResolver::fromSystem());

        self::assertSame('++12x3', $formatter->formatNumber(12.3456, ['padding_position' => 'after_prefix'], 'decimal', 'default', 'fr'));
    }

    /** @test */
    public function it_can_handle_date_type(): void
    {
        $dateString = '2019-08-07 23:39:12';
        $dateImmutable = new DateTimeImmutable('2019-08-07 23:39:12');
        $date = new DateTime('2019-08-07 23:39:12');

        self::assertSame('Jun 3, 2022', $this->formatter->formatDate(1654247542, 'medium'));
        self::assertSame('Jun 3, 2022', $this->formatter->formatDate('1654247542', 'medium'));
        self::assertSame($this->formatter->formatDate(null), $this->formatter->formatDate('NoW'));
        self::assertSame($this->formatter->formatDate($date), $this->formatter->formatDate($dateImmutable));
        self::assertSame($this->formatter->formatDate($date), $this->formatter->formatDate($dateString));
        self::assertSame(
            $this->formatter->formatDate($dateString, 'full', null, 'Africa/Kinshasa'),
            $this->formatter->formatDate($dateString, 'full', null, new DateTimeZone('Africa/Kinshasa'))
        );

        self::assertNotSame(
            $this->formatter->formatDateTime($dateString, 'full', 'full', null, 'Africa/Kinshasa'),
            $this->formatter->formatDateTime($dateString, 'full', 'full', null, false)
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

        $this->formatter->formatDate('2019-08-07 23:39:12', 'foobar');
    }

    /** @test */
    public function it_fails_to_format_a_time_with_an_invalid_time_format(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatTime('2019-08-07 23:39:12', 'foobar');
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_style(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, [], 'foobar');
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_type(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, [], 'decimal', 'foobar');
    }

    /** @test */
    public function it_fails_to_format_a_number_with_unknown_attributes(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, ['foobar' => 1]);
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_rouding_mode_attributes(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, ['rounding_mode' => 'foobar']);
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_padding_position_attributes(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, ['padding_position' => 'foobar']);
    }

    /** @test */
    public function it_fails_to_format_a_number_with_invalid_attributes_value(): void
    {
        $this->expectException(FailedFormatting::class);

        $this->formatter->formatNumber(42, ['grouping_used' => 'foobar']);
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
