<?php
namespace App\Helpers;

class Signature {
    private static $configPath;

    private static function getConfigPath() {
        if (!self::$configPath) {
            // Root path of the application
            self::$configPath = dirname(__DIR__, 2) . '/openssl.cnf';
        }
        return self::$configPath;
    }

    /**
     * Generate a new key pair and a unique signature block for a user.
     * 
     * @param string $name User's Full Name (Common Name)
     * @param string $email User's Email
     * @return array ['signature_block' => string, 'private_key' => string, 'public_key' => string] or false on error
     */
    public static function generate($name, $email) {
        $configPath = self::getConfigPath();
        
        if (!file_exists($configPath)) {
            // Log error or throw exception
            error_log("OpenSSL config not found at: " . $configPath);
            return false;
        }

        $dn = [
            "countryName" => "TR",
            "stateOrProvinceName" => "Istanbul",
            "localityName" => "Merkez",
            "organizationName" => "Edebiyat Bilimleri Dergisi",
            "organizationalUnitName" => "Yazar",
            "commonName" => $name,
            "emailAddress" => $email
        ];

        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "config" => $configPath
        ];

        // 1. Create Private Key
        $privKeyRes = openssl_pkey_new($config);
        if ($privKeyRes === false) {
            error_log("OpenSSL Error: " . openssl_error_string());
            return false;
        }

        // 2. Export Private Key
        openssl_pkey_export($privKeyRes, $privateKey, null, $config);

        // 3. Get Public Key
        $details = openssl_pkey_get_details($privKeyRes);
        $publicKey = $details['key'];

        // 4. Create a self-signed signature of the user's identity data
        // This acts as a consistent "visual" representation of their digital signature
        $identityData = "Signed by: $name ($email) | Date: " . date("Y-m-d H:i:s");
        
        $binarySignature = "";
        openssl_sign($identityData, $binarySignature, $privateKey, OPENSSL_ALGO_SHA256);
        
        $base64Signature = base64_encode($binarySignature);
        $chunkedParams = chunk_split($base64Signature, 64, "\n"); // Standard PEM formatting width

        $signatureBlock = "-----BEGIN USER IDENTITY SIGNATURE-----\n";
        $signatureBlock .= "Hash: SHA256\n\n"; // Imitating PGP-style blocks for readability
        $signatureBlock .= $chunkedParams;
        $signatureBlock .= "-----END USER IDENTITY SIGNATURE-----";

        return [
            'signature_block' => $signatureBlock,
            'private_key' => $privateKey, // Only needed if we want them to sign arbitrary data later on the fly using THIS key
            'public_key' => $publicKey
        ];
    }
}
