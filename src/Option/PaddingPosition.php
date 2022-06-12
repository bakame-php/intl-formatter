<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

/**
 * @method static self BeforePrefix()
 * @method static self AfterPrefix()
 * @method static self BeforeSuffix()
 * @method static self AfterSuffix()
 */
final class PaddingPosition extends BackedEnumPolyfill
{
    public const BeforePrefix = 'before_prefix';
    public const AfterPrefix = 'after_prefix';
    public const BeforeSuffix = 'before_suffix';
    public const AfterSuffix = 'after_suffix';

    protected const INTL_MAPPER = [
        self::BeforePrefix => NumberFormatter::PAD_BEFORE_PREFIX,
        self::AfterPrefix => NumberFormatter::PAD_AFTER_PREFIX,
        self::BeforeSuffix => NumberFormatter::PAD_BEFORE_SUFFIX,
        self::AfterSuffix => NumberFormatter::PAD_AFTER_SUFFIX,
    ];

    protected static string $description = 'padding position';
}
