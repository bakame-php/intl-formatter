<?php

declare(strict_types=1);

namespace Bakame\Intl;

use Bakame\Intl\Options\Calendar;
use Bakame\Intl\Options\DateType;
use Bakame\Intl\Options\TimeType;
use IntlDateFormatter;
use PHPUnit\Framework\TestCase;

final class DateFactoryTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_only_required_properties(): void
    {
        $config = new DateFactory(
            DateType::fromName('full'),
            TimeType::fromName('short'),
            Calendar::fromName('traditional'),
            null
        );

        self::assertNull($config->pattern);
        self::assertSame(IntlDateFormatter::TRADITIONAL, $config->calendar->value);
        self::assertSame(IntlDateFormatter::FULL, $config->dateType->value);
        self::assertSame(IntlDateFormatter::SHORT, $config->timeType->value);
    }

    /** @test */
    public function it_can_be_instantiated_with_only_required_properties_via_settings(): void
    {
        $config = DateFactory::fromAssociative([
            'dateFormat' => 'full',
            'timeFormat' => 'short',
            'calendar' => 'gregorian',
        ]);
        self::assertNull($config->pattern);
        self::assertSame(IntlDateFormatter::GREGORIAN, $config->calendar->value);
        self::assertSame(IntlDateFormatter::FULL, $config->dateType->value);
        self::assertSame(IntlDateFormatter::SHORT, $config->timeType->value);
    }

    /** @test */
    public function it_fails_load_configuration_with_invalid_attribute_name(): void
    {
        $this->expectException(FailedFormatting::class);

        DateFactory::fromAssociative([
            'dateFormat' => 'full',
            'timeFormat' => 'short',
            'calendar' => 'foobar',
        ]);
    }
}
