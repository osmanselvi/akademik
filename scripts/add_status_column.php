<?php
require_once __DIR__ . '/../bootstrap.php';

try {
    echo "Checking schema for 'gonderilen_makale'...\n";

    // Check if status column exists
    $stmt = $baglanti->query("SHOW COLUMNS FROM gonderilen_makale LIKE 'status'");
    if ($stmt->rowCount() == 0) {
        $baglanti->exec("ALTER TABLE gonderilen_makale ADD COLUMN status TINYINT DEFAULT 0");
        echo "Added 'status' column to 'gonderilen_makale' table.\n";
    } else {
        echo "'status' column already exists in 'gonderilen_makale' table.\n";
    }
    
    // Also check for user_id vs created_by? Schema says created_by. Code likely uses proper mapping.
    
    echo "Schema update check complete.\n";

} catch (PDOException $e) {
    die("Error updating database: " . $e->getMessage() . "\n");
}
