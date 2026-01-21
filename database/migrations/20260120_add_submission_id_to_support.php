<?php
/**
 * Migration: Add submission_id to support requests
 */

require_once __DIR__ . '/../../bootstrap.php';

$baglanti = getDatabase();

$sql = "ALTER TABLE destek_talepleri ADD COLUMN submission_id INT NULL AFTER user_id;";

try {
    $baglanti->exec($sql);
    echo "submission_id kolonu başarıyla eklendi.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Kolon zaten mevcut.\n";
    } else {
        die("Hata: " . $e->getMessage() . "\n");
    }
}
