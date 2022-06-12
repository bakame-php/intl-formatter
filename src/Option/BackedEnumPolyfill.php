<?php

declare(strict_types=1);

namespace Bakame\Intl\Option;

use Bakame\Intl\FailedFormatting;
use LogicException;
use ReflectionClass;
use Throwable;

/**
 * @internal
 */
abstract class BackedEnumPolyfill
{
    protected const INTL_MAPPER = [];
    /**
     * @var array<class-string,array<string, string>>
     */
    protected static array $constants = [];
    protected static string $description = '';

    /** @readonly */
    public string $value;

    final protected function __construct(string $value)
    {
        /** @var string|false $name */
        $name = array_search($value, static::constants(), true);
        if (!is_string($name)) {
            throw FailedFormatting::dueToUnknownOptions(static::$description, $value, static::constants());
        }

        $this->value = $value;
    }

    /**
     * @return static
     */
    public static function from(string $name): self
    {
        return new static($name);
    }

    /**
     * @return static
     */
    public static function fromIntlConstant(int $value): self
    {
        /** @var string|false $name */
        $name = array_search($value, static::INTL_MAPPER, true);
        if (!is_string($name)) {
            throw new FailedFormatting('Unsupported constants.');
        }

        return static::from($name);
    }

    public function toIntlConstant(): int
    {
        return static::INTL_MAPPER[$this->value];
    }


    /**
     * @return static
     */
    public static function tryFrom(string $value): ?self
    {
        try {
            return static::from($value);
        } catch (FailedFormatting $exception) {
            return null;
        }
    }

    final public function __clone()
    {
        throw new LogicException('Enums are not cloneable');
    }

    final public function __sleep()
    {
        throw new LogicException('Enums are not cloneable');
    }

    final public function __wakeup()
    {
        throw new LogicException('Enums are not serializable');
    }

    /**
     * @param array<mixed> $args
     *
     * @return static
     */
    public static function __callStatic(string $method, array $args = []): self
    {
        try {
            /** @var string $const */
            $const = constant(static::class.'::'.$method);
        } catch (Throwable $exception) {
            throw FailedFormatting::dueToUnknownOptions(static::$description, $method, array_keys(static::constants()));
        }

        return static::from($const);
    }

    /**
     * @return array<string, string>
     */
    final protected static function constants(): array
    {
        if (array_key_exists(static::class, static::$constants)) {
            return static::$constants[static::class];
        }

        static::$constants[static::class] = [];
        $reflection = new ReflectionClass(static::class);
        foreach ($reflection->getReflectionConstants() as $reflConstant) {
            if ($reflConstant->isPublic()) {
                /** @var string $value */
                $value =  $reflConstant->getValue();
                static::$constants[static::class][$reflConstant->getName()] = $value;
            }
        }

        return static::$constants[static::class];
    }
}
