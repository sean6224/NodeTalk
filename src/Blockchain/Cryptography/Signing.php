<?php
declare(strict_types=1);
namespace App\Blockchain\Cryptography;

use App\Blockchain\Interfaces\Cryptography\SigningInterface;
use App\Blockchain\Exceptions\Cryptography\SigningException;

class Signing implements SigningInterface
{
    /**
     * Signs data using a private key.
     *
     * @param string $data Data to sign.
     * @param string $privateKey Private key for signing.
     * @return string Signature for the data.
     * @throws SigningException If the private key is invalid or signing fails.
     */
    public static function sign(string $data, string $privateKey): string
    {
        if (!openssl_get_privatekey($privateKey)) {
            throw SigningException::invalidPrivateKey();
        }

        $signature = '';
        $success = openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$success) {
            throw SigningException::signingFailed();
        }

        return base64_encode($signature);
    }

    /**
     * Verifies a signature using a public key.
     *
     * @param string $data Data that was signed.
     * @param string $signature Signature to verify.
     * @param string $publicKey Public key for verification.
     * @return bool True if the signature is valid, false otherwise.
     * @throws SigningException If the public key is invalid or verification fails.
     */
    public static function verify(string $data, string $signature, string $publicKey): bool
    {
        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if ($publicKeyResource === false) {
            throw SigningException::invalidPublicKey();
        }

        $decodedSignature = base64_decode($signature);
        $success = openssl_verify($data, $decodedSignature, $publicKeyResource, OPENSSL_ALGO_SHA256);

        if ($success === -1) {
            throw SigningException::signatureVerificationFailed();
        }
        return $success === 1;
    }
}
