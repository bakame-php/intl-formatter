<?php

declare(strict_types=1);

namespace Bakame\Intl;

use NumberFormatter;
use PHPUnit\Framework\TestCase;

final class NumberFactoryTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_only_required_properties(): void
    {
        $config = NumberFactory::fromAssociative(['style' => 'currency', 'attributes' => ['grouping_used' => 2]]);

        self::assertCount(1, $config->attributes);
        self::assertSame(NumberFormatter::GROUPING_USED, $config->attributes[0]->name->toIntlConstant());
        self::assertSame(2, $config->attributes[0]->value);
        self::assertCount(0, $config->textAttributes);
        self::assertCount(0, $config->symbolAttributes);
        self::assertEmpty($config->pattern);
        self::assertSame(NumberFormatter::CURRENCY, $config->style->toIntlConstant());
    }

    /** @test */
    public function it_can_be_instantiated_with_only_required_properties_via_settings(): void
    {
        $config = NumberFactory::fromAssociative(['style' => 'currency']);

        self::assertCount(0, $config->attributes);
        self::assertCount(0, $config->textAttributes);
        self::assertCount(0, $config->symbolAttributes);
        self::assertNull($config->pattern);
        self::assertSame(NumberFormatter::CURRENCY, $config->style->toIntlConstant());
    }

    /** @test */
    public function it_fails_load_configuration_with_invalid_attribute_name(): void
    {
        $this->expectException(FailedFormatting::class);

        NumberFactory::fromAssociative([
            'style' => 'currency',
            'attributes' => ['foobar' => 1],
        ]);
    }

    /** @test */
    public function it_fails_load_configuration_with_invalid_attribute_value(): void
    {
        $this->expectException(FailedFormatting::class);

        NumberFactory::fromAssociative([
            'style' => 'currency',
            'attributes' => ['grouping_used' => '2'],
        ]);
    }
}
