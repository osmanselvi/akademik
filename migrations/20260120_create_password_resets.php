<?php
require_once __DIR__ . '/../bootstrap.php';

$pdo = getDatabase();

try {
    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        email VARCHAR(255) NOT NULL,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (email),
        INDEX (token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "password_resets tablosu başarıyla oluşturuldu.\n";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
