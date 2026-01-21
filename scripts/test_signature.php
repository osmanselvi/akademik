<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Helpers\Signature;

echo "Testing Signature Helper...\n";

$name = "Test User";
$email = "test@example.com";

// Test Generation
$result = Signature::generate($name, $email);

if ($result === false) {
    echo "[FAIL] Signature generation returned false.\n";
    
    // Check if openssl.cnf exists
    $configPath = dirname(__DIR__, 2) . '/openssl.cnf';
    if (!file_exists($configPath)) {
        echo "Config file not found at: $configPath\n";
    } else {
        echo "Config file exists at: $configPath\n";
        // Check contents
        echo "Config content preview: " . substr(file_get_contents($configPath), 0, 50) . "...\n";
    }
    
    exit(1);
}

echo "[PASS] Signature generated successfully.\n";

if (strpos($result['signature_block'], 'BEGIN USER IDENTITY SIGNATURE') !== false) {
    echo "[PASS] Signature block format is correct.\n";
} else {
    echo "[FAIL] Signature block format incorrect.\n";
}

if (!empty($result['private_key']) && !empty($result['public_key'])) {
    echo "[PASS] Private and Public keys generated.\n";
} else {
    echo "[FAIL] Missing keys.\n";
}

// Show preview
echo "\n--- Signature Block Preview ---\n";
echo substr($result['signature_block'], 0, 200) . "...\n";
echo "-------------------------------\n";
