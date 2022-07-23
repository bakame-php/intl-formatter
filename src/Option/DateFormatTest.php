<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use Bakame\Intl\FailedFormatting;
use IntlDateFormatter;
use LogicException;
use PHPUnit\Framework\TestCase;

final class DateFormatTest extends TestCase
{
    /** @test */
    public function it_can_return_a_data_type_objec(): void
    {
        self::assertSame('none', DateFormat::fromIntlConstant(IntlDateFormatter::NONE)->value);
        self::assertSame(IntlDateFormatter::FULL, DateFormat::from('full')->toIntlConstant());
        self::assertEquals(DateFormat::Full(), DateFormat::from('full'));
    }

    /** @test */
    public function it_fails_with_invalid_constant_name(): void
    {
        $this->expectException(FailedFormatting::class);

        DateFormat::Foobar(); /* @phpstan-ignore-line */
    }

    /** @test */
    public function it_fails_with_invalid_constant_value(): void
    {
        $this->expectException(FailedFormatting::class);

        DateFormat::from('foobar');
    }

    /** @test */
    public function it_throws_with_invalid_constant_value_using_from(): void
    {
        $this->expectException(FailedFormatting::class);

        DateFormat::fromIntlConstant(PHP_INT_MAX);
    }

    /** @test */
    public function it_returns_null_with_invalid_constant_value_using_tryfrom(): void
    {
        self::assertNull(DateFormat::tryFrom('foobar'));
    }

    /** @test */
    public function it_throws_if_you_want_to_clone_it(): void
    {
        $this->expectException(LogicException::class);

        clone DateFormat::Full();
    }

    /** @test */
    public function it_throws_if_you_want_to_serialize_it(): void
    {
        $this->expectException(LogicException::class);

        serialize(DateFormat::Full());
    }

    /** @test */
    public function it_throws_if_you_want_to_unserialize_it(): void
    {
        $this->expectException(LogicException::class);

        DateFormat::Full()->__wakeup();
    }
}
