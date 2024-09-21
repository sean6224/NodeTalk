<?php
declare(strict_types=1);
namespace App\Shared\ValueObject;

/**
 * Abstract class FloatValue
 *
 * This class represents a value object for float values. It encapsulates a float value
 * and provides methods for value comparison, instantiation from a float, and string representation.
 */
abstract class FloatValue
{
    protected function __construct(
        protected float $value
    ) {
    }

    public static function fromFloat(float $value): static
    {
        return new static($value);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->value === $other->value;
    }
}
