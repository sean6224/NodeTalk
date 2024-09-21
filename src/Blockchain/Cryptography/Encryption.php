<?php
declare(strict_types=1);
namespace App\Blockchain\Cryptography;

use App\Blockchain\Interfaces\Cryptography\EncryptionInterface;
use App\Blockchain\Exceptions\Cryptography\EncryptionException;
use Random\RandomException;

/**
 * Class Encryption
 *
 * Provides symmetric and asymmetric encryption functions.
 */
class Encryption implements EncryptionInterface
{
    private const string ALGORITHM = 'aes-256-cbc';
    private const int IV_LENGTH = 16; // AES uses a 16-byte IV
    public const int KEY_LENGTH = 32; // AES-256 requires a 32-byte key

    /**
     * Encrypts data using a symmetric key (AES-256).
     *
     * @param string $data Data to encrypt.
     * @param string $key Encryption key (must be 32 bytes for AES-256).
     * @return string Encrypted data.
     * @throws RandomException
     * @throws EncryptionException
     */
    public static function encryptSymmetric(string $data, string $key): string
    {
        if (strlen($key) !== self::KEY_LENGTH) {
            throw EncryptionException::invalidKeyLength();
        }

        $iv = random_bytes(self::IV_LENGTH);
        $encrypted = openssl_encrypt($data, self::ALGORITHM, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw EncryptionException::encryptionFailed();
        }

        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypts data using a symmetric key (AES-256).
     *
     * @param string $encryptedData Encrypted data to decrypt.
     * @param string $key Decryption key (must be 32 bytes for AES-256).
     * @return string Decrypted data.
     * @throws EncryptionException
     */
    public static function decryptSymmetric(string $encryptedData, string $key): string
    {
        if (strlen($key) !== self::KEY_LENGTH) {
            throw EncryptionException::invalidKeyLength();
        }

        $data = base64_decode($encryptedData, true);
        if ($data === false) {
            throw EncryptionException::decodingFailed();
        }

        $iv = substr($data, 0, self::IV_LENGTH);
        $encrypted = substr($data, self::IV_LENGTH);
        $decrypted = openssl_decrypt($encrypted, self::ALGORITHM, $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw EncryptionException::decryptionFailed();
        }

        return $decrypted;
    }

    /**
     * Encrypts data using a public key (asymmetric encryption).
     *
     * @param string $data Data to encrypt.
     * @param string $publicKey Public key for encryption.
     * @return string Encrypted data.
     * @throws EncryptionException
     */
    public static function encryptAsymmetric(string $data, string $publicKey): string
    {
        $encrypted = null;
        if (!openssl_public_encrypt($data, $encrypted, $publicKey)) {
            throw EncryptionException::encryptionFailed();
        }
        return $encrypted;
    }

    /**
     * Decrypts data using a private key (asymmetric decryption).
     *
     * @param string $encryptedData Encrypted data.
     * @param string $privateKey Private key for decryption.
     * @return string Decrypted data.
     * @throws EncryptionException
     */
    public static function decryptAsymmetric(string $encryptedData, string $privateKey): string
    {
        $decrypted = null;
        if (!openssl_private_decrypt($encryptedData, $decrypted, $privateKey)) {
            throw EncryptionException::decryptionFailed();
        }
        return $decrypted;
    }
}
