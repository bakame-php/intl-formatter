<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use Bakame\Intl\FailedFormatting;
use LogicException;
use ReflectionClass;
use ReflectionClassConstant;
use Throwable;

/**
 * @internal
 */
abstract class BackedEnumPolyfill
{
    protected const INTL_MAPPER = [];
    /** @var array<class-string,array<array{name:string, value:static}>> */
    protected static array $cases = [];
    protected static string $description = '';

    /** @readonly */
    public string $value;

    final protected function __construct(string $value)
    {
        $this->value = $value;
    }

    final public static function from(string $name): static
    {
        static::cases();

        if (!isset(static::$cases[static::class][$name])) {
            throw FailedFormatting::dueToUnknownOptions(static::$description, $name, array_keys(static::$cases[static::class]));
        }

        return static::$cases[static::class][$name]['value'];
    }

    final public static function tryFrom(string $value): ?static
    {
        try {
            return static::from($value);
        } catch (FailedFormatting) {
            return null;
        }
    }

    /**
     * @return array<static>
     */
    final public static function cases(): array
    {
        if (!array_key_exists(static::class, static::$cases)) {
            $reflection = new ReflectionClass(static::class);
            static::$cases[static::class] = array_reduce(
                $reflection->getReflectionConstants(ReflectionClassConstant::IS_PUBLIC),
                function (array $curry, ReflectionClassConstant $constant): array {
                    /** @var string $value */
                    $value = $constant->getValue();
                    $curry[$value] = ['name' => $constant->getName(), 'value' => new static($value)];

                    return $curry;
                },
                []
            );
        }

        return array_values(array_column(static::$cases[static::class], 'value'));
    }

    /**
     * @param mixed[] $args
     */
    final public static function __callStatic(string $method, array $args = []): static
    {
        try {
            /** @var string $const */
            $const = constant(static::class.'::'.$method);
        } catch (Throwable) {
            throw FailedFormatting::dueToUnknownOptions(static::$description, $method, array_values(array_column(static::$cases[static::class], 'name')));
        }

        return static::from($const);
    }

    final public static function fromIntlConstant(int $value): static
    {
        /** @var string|false $name */
        $name = array_search($value, static::INTL_MAPPER, true);
        if (false === $name) {
            throw new FailedFormatting('Unsupported constants.');
        }

        return static::from($name);
    }

    final public function toIntlConstant(): int
    {
        return static::INTL_MAPPER[$this->value];
    }

    final public function __clone()
    {
        throw new LogicException('Enums are not cloneable');
    }

    final public function __sleep()
    {
        throw new LogicException('Enums are not serializable');
    }

    final public function __wakeup()
    {
        throw new LogicException('Enums are not serializable');
    }
}
