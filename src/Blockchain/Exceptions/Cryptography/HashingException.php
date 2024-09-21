<?php
declare(strict_types=1);
namespace App\Blockchain\Exceptions\Cryptography;

use Exception;
use RuntimeException;

/**
 * Exception related to hashing operations in cryptography.
 */
class HashingException extends RuntimeException
{
    public static function unsupportedAlgorithm(string $algorithm): self
    {
        return new self("Hashing algorithm '$algorithm' is not supported.");
    }

    public static function invalidSaltLength(int $length): self
    {
        return new self("Salt length must be greater than 0. Given: $length.");
    }

    public static function saltGenerationFailed(Exception $previous): self
    {
        return new self("Failed to generate a random salt.", 0, $previous);
    }
}
