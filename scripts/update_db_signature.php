<?php
require_once __DIR__ . '/../bootstrap.php';

try {
    echo "Updating database schema for E-Signature...\n";

    // 1. Add e_imza column to yonetim table if not exists
    $stmt = $baglanti->query("SHOW COLUMNS FROM yonetim LIKE 'e_imza'");
    if ($stmt->rowCount() == 0) {
        $baglanti->exec("ALTER TABLE yonetim ADD COLUMN e_imza TEXT DEFAULT NULL");
        echo "Added 'e_imza' column to 'yonetim' table.\n";
    } else {
        echo "'e_imza' column already exists in 'yonetim' table.\n";
    }

    // 2. Create imza_loglari table
    $sql = "CREATE TABLE IF NOT EXISTS imza_loglari (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        makale_id INT DEFAULT NULL,
        sozlesme_metni LONGTEXT,
        imza_kodu TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(50),
        INDEX (user_id),
        INDEX (makale_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $baglanti->exec($sql);
    echo "Created 'imza_loglari' table (if it didn't exist).\n";

    echo "Database update completed successfully.\n";

} catch (PDOException $e) {
    die("Error updating database: " . $e->getMessage() . "\n");
}
