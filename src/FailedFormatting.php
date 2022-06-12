<?php

declare(strict_types=1);

namespace Bakame\Intl;

use RuntimeException;
use Throwable;

final class FailedFormatting extends RuntimeException
{
    /** @codeCoverageIgnore */
    public static function dueToNumberFormatter(string $message): self
    {
        return new self($message);
    }

    /** @codeCoverageIgnore */
    public static function dueToDateFormatter(string $message): self
    {
        return new self($message);
    }

    public static function dueToInvalidDate(Throwable $exception = null): self
    {
        return new self('Unable to format the given date.', 0, $exception);
    }

    /**
     * @param array<string> $supported
     */
    public static function dueToUnknownOptions(string $name, string $format, array $supported): self
    {
        return new self('The '.$name.' "'.$format.'" does not exist; expecting one value of : "'.implode('", "', $supported).'".');
    }

    /**
     * @param int|float|string $value
     */
    public static function dueToInvalidNumberFormatterAttributeValue(string $name, $value): self
    {
        return new self('The number formatter value for "'.$name.'" can not be a string: "'.$value.'"');
    }
}
