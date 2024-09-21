<?php
declare(strict_types=1);
namespace App\Blockchain\Interfaces\Cryptography;

/**
 * Interface EncryptionInterface
 *
 * Defines methods for symmetric and asymmetric encryption and decryption.
 */
interface EncryptionInterface
{
    /**
     * Encrypts data using a symmetric key.
     *
     * @param string $data Data to encrypt.
     * @param string $key Encryption key.
     * @return string Encrypted data.
     */
    public static function encryptSymmetric(string $data, string $key): string;

    /**
     * Decrypts data using a symmetric key.
     *
     * @param string $encryptedData Encrypted data to decrypt.
     * @param string $key Decryption key.
     * @return string Decrypted data.
     */
    public static function decryptSymmetric(string $encryptedData, string $key): string;

    /**
     * Encrypts data using a public key.
     *
     * @param string $data Data to encrypt.
     * @param string $publicKey Public key.
     * @return string Encrypted data.
     */
    public static function encryptAsymmetric(string $data, string $publicKey): string;

    /**
     * Decrypts data using a private key.
     *
     * @param string $encryptedData Encrypted data to decrypt.
     * @param string $privateKey Private key.
     * @return string Decrypted data.
     */
    public static function decryptAsymmetric(string $encryptedData, string $privateKey): string;
}
