<?php

namespace App\Service;

use App\Entity\User;
use OpenSSLAsymmetricKey;
use RuntimeException;

final readonly class Decryptor
{
    public function hasKeysStored(User $user): bool
    {
        return $user->getEncryptionKey() !== null;
    }

    public function decrypt(OpenSSLAsymmetricKey $decryptionKey, string $encryptedData): string
    {
        $encryptedData = base64_decode($encryptedData);

        $success = openssl_private_decrypt($encryptedData, $decrypted, $decryptionKey, OPENSSL_PKCS1_OAEP_PADDING);

        if (!$success) {
            throw new RuntimeException('Decryption failed');
        }

        return $decrypted;
    }

    public function getPrivateKey(User $user): OpenSSLAsymmetricKey
    {
        $privateKey = $this->getPrivateKeyString($user);

        $privateKeyContent = "-----BEGIN PRIVATE KEY-----\n"
            . chunk_split($privateKey, 64, "\n")
            . "-----END PRIVATE KEY-----";
        return openssl_pkey_get_private($privateKeyContent)
            ?: throw new RuntimeException('Failed getting private key');
    }

    private function getPrivateKeyString(User $user): string
    {
        return $user->getEncryptionKey() !== null
            ? explode(':::', $user->getEncryptionKey())[0]
            : throw new RuntimeException('No encryption keys stored');
    }
}
