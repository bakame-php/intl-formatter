<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Options\Calendar;
use Bakame\Intl\Options\DateType;
use Bakame\Intl\Options\NumberAttribute;
use Bakame\Intl\Options\NumberStyle;
use Bakame\Intl\Options\TimeType;
use IntlDateFormatter;
use NumberFormatter;
use PHPUnit\Framework\TestCase;

final class ConfigurationTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_only_required_properties(): void
    {
        $config = new Configuration(
            DateType::fromName('full'),
            TimeType::fromName('short'),
            NumberStyle::fromName('currency'),
            Calendar::fromName('gregorian'),
            null,
            '',
            [NumberAttribute::from('grouping_used', 2)],
        );

        self::assertCount(1, $config->attributes);
        self::assertSame(NumberFormatter::GROUPING_USED, $config->attributes[0]->name);
        self::assertSame(2, $config->attributes[0]->value);
        self::assertCount(0, $config->textAttributes);
        self::assertCount(0, $config->symbolAttributes);
        self::assertNull($config->datePattern);
        self::assertEmpty($config->numberPattern);
        self::assertSame(IntlDateFormatter::GREGORIAN, $config->calendar->value);
        self::assertSame(IntlDateFormatter::FULL, $config->dateType->value);
        self::assertSame(IntlDateFormatter::SHORT, $config->timeType->value);
        self::assertSame(NumberFormatter::CURRENCY, $config->style->value);
    }

    /** @test */
    public function it_can_be_instantiated_with_only_required_properties_via_settings(): void
    {
        $config = Configuration::fromApplication([
            'date' => [
                'dateFormat' => 'full',
                'timeFormat' => 'short',
            ],
            'number' => [
                'style' => 'currency',
            ],
        ]);

        self::assertCount(0, $config->attributes);
        self::assertCount(0, $config->textAttributes);
        self::assertCount(0, $config->symbolAttributes);
        self::assertNull($config->datePattern);
        self::assertNull($config->numberPattern);
        self::assertSame(IntlDateFormatter::GREGORIAN, $config->calendar->value);
        self::assertSame(IntlDateFormatter::FULL, $config->dateType->value);
        self::assertSame(IntlDateFormatter::SHORT, $config->timeType->value);
        self::assertSame(NumberFormatter::CURRENCY, $config->style->value);
    }

    /** @test */
    public function it_fails_load_configuration_with_invalid_attribute_name(): void
    {
        $this->expectException(FailedFormatting::class);

        Configuration::fromApplication([
            'date' => [
                'dateFormat' => 'full',
                'timeFormat' => 'short',
            ],
            'number' => [
                'style' => 'currency',
                'attributes' => [
                    'foobar' => 1,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_load_configuration_with_invalid_attribute_value(): void
    {
        $this->expectException(FailedFormatting::class);

        Configuration::fromApplication([
            'date' => [
                'dateFormat' => 'full',
                'timeFormat' => 'short',
            ],
            'number' => [
                'style' => 'currency',
                'attributes' => [
                    'grouping_used' => '2',
                ],
            ],
        ]);
    }
}
