<?php

declare(strict_types=1);

namespace Bakame\Intl;

use IntlDateFormatter;
use PHPUnit\Framework\TestCase;

final class DateFactoryTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_only_required_properties(): void
    {
        $config = new DateFactory(
            Option\DateFormat::from('full'),
            Option\TimeFormat::from('short'),
            Option\CalendarFormat::from('traditional'),
            null
        );

        self::assertNull($config->pattern);
        self::assertSame(IntlDateFormatter::TRADITIONAL, $config->calendar->toIntlConstant());
        self::assertSame(IntlDateFormatter::FULL, $config->dateType->toIntlConstant());
        self::assertSame(IntlDateFormatter::SHORT, $config->timeType->toIntlConstant());
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
        self::assertSame(IntlDateFormatter::GREGORIAN, $config->calendar->toIntlConstant());
        self::assertSame(IntlDateFormatter::FULL, $config->dateType->toIntlConstant());
        self::assertSame(IntlDateFormatter::SHORT, $config->timeType->toIntlConstant());
    }

    /** @test */
    public function it_fails_load_configuration_with_invalid_attribute_name(): void
    {
        $this->expectException(FailedFormatting::class);

        DateFactory::fromAssociative(['dateFormat' => 'full', 'timeFormat' => 'short', 'calendar' => 'foobar']); /* @phpstan-ignore-line */
    }
}
