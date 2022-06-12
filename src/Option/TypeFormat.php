<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

final class TypeFormat extends BackedEnumPolyfill
{
    public const Default = 'default';
    public const Int32 = 'int32';
    public const Int64 = 'int64';
    public const Double = 'double';
    public const Currency = 'currency';

    protected const INTL_MAPPER = [
        self::Default => NumberFormatter::TYPE_DEFAULT,
        self::Int32 => NumberFormatter::TYPE_INT32,
        self::Int64 => NumberFormatter::TYPE_INT64,
        self::Double => NumberFormatter::TYPE_DOUBLE,
        self::Currency => NumberFormatter::TYPE_CURRENCY,
    ];

    protected static string $description = 'type';
}
