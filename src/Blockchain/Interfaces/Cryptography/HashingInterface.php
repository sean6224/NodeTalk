<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\Cryptography;

use App\Blockchain\Exceptions\Cryptography\HashingException;

/**
 * Interface HashingInterface
 *
 * Defines methods for hashing data with various algorithms, generating salts, and verifying hashes.
 */
interface HashingInterface
{
    /**
     * Hashes data using the specified algorithm.
     *
     * @param string $data Data to hash.
     * @param string $algorithm Hashing algorithm (e.g., 'sha256', 'sha3-512').
     * @return string Hashed data.
     * @throws HashingException If the hashing algorithm is not supported.
     */
    public static function hash(string $data, string $algorithm): string;

    /**
     * Checks if the specified hashing algorithm is supported.
     *
     * @param string $algorithm Hashing algorithm.
     * @return bool True if supported, false otherwise.
     */
    public static function isAlgorithmSupported(string $algorithm): bool;

    /**
     * Generates a random salt.
     *
     * @param int $length Length of the salt in bytes.
     * @return string Random salt.
     * @throws HashingException If salt generation fails.
     */
    public static function generateSalt(int $length = 16): string;

    /**
     * Hashes data with a salt using the specified algorithm.
     *
     * @param string $data Data to hash.
     * @param string $salt Salt for hashing.
     * @param string $algorithm Hashing algorithm.
     * @return string Hashed data.
     * @throws HashingException If the algorithm is not supported.
     */
    public static function hashWithSalt(string $data, string $salt, string $algorithm): string;

    /**
     * Verifies if a hash matches the data with a salt.
     *
     * @param string $data Data to check.
     * @param string $salt Salt used in hashing.
     * @param string $expectedHash Expected hash.
     * @param string $algorithm Hashing algorithm.
     * @return bool True if matches, false otherwise.
     */
    public static function verifyHashWithSalt(string $data, string $salt, string $expectedHash, string $algorithm): bool;
}
