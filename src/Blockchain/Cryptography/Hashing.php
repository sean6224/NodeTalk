<?php
declare(strict_types=1);
namespace App\Blockchain\Cryptography;

use App\Blockchain\Interfaces\Cryptography\HashingInterface;
use App\Blockchain\Exceptions\Cryptography\HashingException;
use Exception;

/**
 * Class Hashing
 *
 * Implements the HashingInterface for performing various hashing operations.
 * Provides methods for hashing data using specified algorithms, generating random salts,
 * and verifying hashes with salts.
 *
 **/
class Hashing implements HashingInterface
{
    /**
     * Hashes data using the specified algorithm.
     *
     * @param string $data Data to hash.
     * @param string $algorithm Hashing algorithm (e.g., 'sha256', 'sha3-512').
     * @return string Hashed data.
     * @throws HashingException If the algorithm is not supported.
     */
    public static function hash(string $data, string $algorithm): string
    {
        if (!self::isAlgorithmSupported($algorithm)) {
            throw HashingException::unsupportedAlgorithm($algorithm);
        }

        return hash($algorithm, $data);
    }

    /**
     * Checks if the specified hashing algorithm is supported.
     *
     * @param string $algorithm Hashing algorithm.
     * @return bool True if supported, false otherwise.
     */
    public static function isAlgorithmSupported(string $algorithm): bool
    {
        return in_array($algorithm, hash_algos(), true);
    }

    /**
     * Generates a random salt.
     *
     * @param int $length Length of the salt in bytes.
     * @return string Random salt.
     * @throws HashingException If salt generation fails.
     */
    public static function generateSalt(int $length = 16): string
    {
        if ($length <= 0) {
            throw HashingException::invalidSaltLength($length);
        }

        try {
            $salt = random_bytes($length);
        } catch (Exception $e) {
            throw HashingException::saltGenerationFailed($e);
        }

        return base64_encode($salt);
    }

    /**
     * Hashes data with a salt using the specified algorithm.
     *
     * @param string $data Data to hash.
     * @param string $salt Salt for hashing.
     * @param string $algorithm Hashing algorithm.
     * @return string Hashed data.
     * @throws HashingException If the algorithm is not supported.
     */
    public static function hashWithSalt(string $data, string $salt, string $algorithm): string
    {
        if (!self::isAlgorithmSupported($algorithm)) {
            throw HashingException::unsupportedAlgorithm($algorithm);
        }

        $dataWithSalt = $data . $salt;
        return self::hash($dataWithSalt, $algorithm);
    }

    /**
     * Verifies if a hash matches the data with a salt.
     *
     * @param string $data Data to check.
     * @param string $salt Salt used in hashing.
     * @param string $expectedHash Expected hash.
     * @param string $algorithm Hashing algorithm.
     * @return bool True if matches, false otherwise.
     */
    public static function verifyHashWithSalt(string $data, string $salt, string $expectedHash, string $algorithm): bool
    {
        $computedHash = self::hashWithSalt($data, $salt, $algorithm);
        return hash_equals($expectedHash, $computedHash);
    }
}
