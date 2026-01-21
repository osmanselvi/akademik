<?php
/**
 * Migration: Create Support Requests Table
 */

require_once __DIR__ . '/../../bootstrap.php';

$baglanti = getDatabase();

$sql = "
CREATE TABLE IF NOT EXISTS destek_talepleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    konu VARCHAR(255) NOT NULL,
    mesaj TEXT NOT NULL,
    status TINYINT DEFAULT 0, -- 0: Açık, 1: Yanıtlandı/Kapalı
    editor_notu TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES yonetim(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

try {
    $baglanti->exec($sql);
    echo "Destek talepleri tablosu başarıyla oluşturuldu/güncellendi.\n";
} catch (PDOException $e) {
    die("Tablo oluşturma hatası: " . $e->getMessage() . "\n");
}
