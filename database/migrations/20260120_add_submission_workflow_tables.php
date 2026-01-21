<?php
require 'bootstrap.php';
$db = getDatabase();

try {
    // 1. Add status field to gonderilen_makale
    $db->exec("ALTER TABLE gonderilen_makale ADD COLUMN status TINYINT DEFAULT 0 AFTER is_approved");
    echo "Added 'status' field to gonderilen_makale.\n";

    // 2. Create makale_revizyon_notlari table
    $sql = "CREATE TABLE IF NOT EXISTS makale_revizyon_notlari (
        id INT AUTO_INCREMENT PRIMARY KEY,
        makale_id INT NOT NULL,
        sender_id INT NOT NULL,
        metin TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (makale_id),
        INDEX (sender_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $db->exec($sql);
    echo "Created 'makale_revizyon_notlari' table.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
