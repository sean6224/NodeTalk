<?php
declare(strict_types=1);
namespace App\Shared\ValueObject;

/**
 * Abstract class IntegerValue
 *
 * This class represents a value object for integer values. It encapsulates an integer value
 * and provides methods for value comparison, instantiation from an integer, and retrieval of the value.
 */
abstract class IntegerValue
{
    protected function __construct(
        protected int $value
    ) {
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function Increment(): void
    {
        $this->value++;
    }

    public function decrement(): void
    {
        $this->value--;
    }

    public function isEqual(self $other): bool
    {
        return $this->value() === $other->value();
    }

    public function isLowerThan(self $other): bool
    {
        return $this->value() < $other->value();
    }

    public function isBiggerThan(self $other): bool
    {
        return $this->value() > $other->value();
    }
}
