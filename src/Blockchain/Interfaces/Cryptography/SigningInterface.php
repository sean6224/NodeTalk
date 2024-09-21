<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\Cryptography;

use App\Blockchain\Exceptions\Cryptography\SigningException;

/**
 * Interface SigningInterface
 *
 * Defines methods for signing data and verifying signatures.
 **/
interface SigningInterface
{
    /**
     * Signs data using a private key.
     *
     * @param string $data Data to sign.
     * @param string $privateKey Private key for signing.
     * @return string Signature for the data.
     * @throws SigningException If signing fails or the private key is invalid.
     */
    public static function sign(string $data, string $privateKey): string;

    /**
     * Verifies a signature using a public key.
     *
     * @param string $data Data that was signed.
     * @param string $signature Signature to verify.
     * @param string $publicKey Public key for verification.
     * @return bool True if the signature is valid, false otherwise.
     * @throws SigningException If the verification fails or the public key is invalid.
     */
    public static function verify(string $data, string $signature, string $publicKey): bool;
}
