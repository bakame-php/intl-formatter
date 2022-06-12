<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use NumberFormatter;

/**
 * @method static self PositivePrefix()
 * @method static self PositiveSuffix()
 * @method static self NegativePrefix()
 * @method static self NegativeSuffix()
 * @method static self PaddingCharacter()
 * @method static self CurrencyCode()
 * @method static self DefaultRuleset()
 * @method static self PublicRulesets()
 */
final class TextFormat extends BackedEnumPolyfill
{
    public const PositivePrefix = 'positive_prefix';
    public const PositiveSuffix = 'positive_suffix';
    public const NegativePrefix = 'negative_prefix';
    public const NegativeSuffix = 'negative_suffix';
    public const PaddingCharacter = 'padding_character';
    public const CurrencyCode = 'currency_code';
    public const DefaultRuleset = 'default_ruleset';
    public const PublicRulesets = 'public_rulesets';

    protected const INTL_MAPPER = [
        self::PositivePrefix => NumberFormatter::POSITIVE_PREFIX,
        self::PositiveSuffix => NumberFormatter::POSITIVE_SUFFIX,
        self::NegativePrefix => NumberFormatter::NEGATIVE_PREFIX,
        self::NegativeSuffix => NumberFormatter::NEGATIVE_SUFFIX,
        self::PaddingCharacter => NumberFormatter::PADDING_CHARACTER,
        self::CurrencyCode => NumberFormatter::CURRENCY_CODE,
        self::DefaultRuleset => NumberFormatter::DEFAULT_RULESET,
        self::PublicRulesets => NumberFormatter::PUBLIC_RULESETS,
    ];

    protected static string $description = 'text format';
}
