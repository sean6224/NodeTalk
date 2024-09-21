<?php
declare(strict_types=1);
namespace App\Blockchain\Exceptions\Cryptography;

use RuntimeException;

/**
 * Exception related to encryption operations in cryptography.
 */
class EncryptionException extends RuntimeException
{
    public static function invalidKeyLength(): self
    {
        return new self("Encryption key must be 32 bytes for AES-256.");
    }

    public static function encryptionFailed(): self
    {
        return new self("Encryption process failed.");
    }

    public static function decryptionFailed(): self
    {
        return new self("Decryption process failed.");
    }

    public static function decodingFailed(): self
    {
        return new self("Base64 decoding failed.");
    }
}
