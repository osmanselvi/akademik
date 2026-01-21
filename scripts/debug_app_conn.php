<?php
// Load the specific bootstrap file to match app environment exactly
require_once __DIR__ . '/../bootstrap.php';

echo "App Debug Info:\n";
try {
    // Check current DB name
    $stmt = $baglanti->query("SELECT DATABASE()");
    $dbName = $stmt->fetchColumn();
    echo "Connected Database: " . $dbName . "\n";

    // Check columns of gonderilen_makale
    $stmt = $baglanti->query("SHOW COLUMNS FROM gonderilen_makale");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Columns in gonderilen_makale:\n";
    print_r($columns);

    if (in_array('status', $columns)) {
        echo "SUCCESS: 'status' column FOUND.\n";
    } else {
        echo "FAILURE: 'status' column NOT FOUND.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
