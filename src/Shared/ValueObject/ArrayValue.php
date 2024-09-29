<?php
declare(strict_types=1);
namespace App\Shared\ValueObject;

use InvalidArgumentException;

/**
 * Abstract class ArrayValue
 *
 * This class represents a value object for array values. It encapsulates an array value
 * and provides methods for value manipulation and retrieval of the array.
 */
abstract class ArrayValue
{
    protected function __construct(
        protected array $value
    ) {
    }

    public static function fromArray(array $value): static
    {
        return new static($value);
    }

    public function addItem(string $key, string $value): void
    {
        if (array_key_exists($key, $this->value)) {
            throw new InvalidArgumentException("Key already exists.");
        }
        $this->value[$key] = $value;
    }

    public function getItem(string $key): ?string
    {
        return $this->value[$key] ?? null;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return json_encode($this->value);
    }

    public function isEqual(self $other): bool
    {
        return $this->value === $other->value();
    }
}
