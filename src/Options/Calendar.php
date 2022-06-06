<?php

declare(strict_types=1);

namespace Bakame\Intl\Options;

use IntlDateFormatter;

final class Calendar extends PseudoEnum
{
    protected const CONSTANTS = [
        'gregorian' => IntlDateFormatter::GREGORIAN,
        'traditional' => IntlDateFormatter::TRADITIONAL,
    ];

    protected static string $description = 'calendar name';
}
