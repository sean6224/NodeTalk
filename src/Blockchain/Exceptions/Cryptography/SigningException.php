<?php
declare(strict_types=1);
namespace App\Blockchain\Exceptions\Cryptography;

use RuntimeException;

/**
 * Exception related to signing operations in cryptography.
 */
class SigningException extends RuntimeException
{
    public static function invalidPrivateKey(): self
    {
        return new self("Invalid private key provided.");
    }

    public static function invalidPublicKey(): self
    {
        return new self("Invalid public key provided.");
    }

    public static function signingFailed(): self
    {
        return new self("Signing process failed.");
    }

    public static function signatureVerificationFailed(): self
    {
        return new self("Signature verification failed.");
    }
}
