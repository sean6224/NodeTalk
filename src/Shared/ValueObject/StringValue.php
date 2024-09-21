<?php
declare(strict_types=1);
namespace App\Shared\ValueObject;

/**
 * Abstract class StringValue
 *
 * This class represents a value object for string values. It encapsulates a string value
 * and provides methods for value comparison, instantiation from a string, and retrieval of the value.
 */
abstract class StringValue
{
    protected function __construct(
        protected string $value
    ) {
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function isEqual(self $other): bool
    {
        return (string) $this === (string) $other;
    }
}
