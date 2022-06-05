<?php

declare(strict_types=1);

namespace Bakame\Intl\Options;

use IntlDateFormatter;

if (7 < PHP_MAJOR_VERSION && extension_loaded('intl')) {
    final class DateType extends PseudoEnum
    {
        protected const CONSTANTS = [
            'none' => IntlDateFormatter::NONE,
            'short' => IntlDateFormatter::SHORT,
            'medium' => IntlDateFormatter::MEDIUM,
            'long' => IntlDateFormatter::LONG,
            'full' => IntlDateFormatter::FULL,
            'relative_short' => IntlDateFormatter::RELATIVE_SHORT,
            'relative_medium' => IntlDateFormatter::RELATIVE_MEDIUM,
            'relative_long' => IntlDateFormatter::RELATIVE_LONG,
            'relative_full' => IntlDateFormatter::RELATIVE_FULL,
        ];

        protected static string $description = 'date format';
    }
} else {
    final class DateType extends PseudoEnum
    {
        protected const CONSTANTS = [
            'none' => IntlDateFormatter::NONE,
            'short' => IntlDateFormatter::SHORT,
            'medium' => IntlDateFormatter::MEDIUM,
            'long' => IntlDateFormatter::LONG,
            'full' => IntlDateFormatter::FULL,
        ];

        protected static string $description = 'date format';
    }
}
