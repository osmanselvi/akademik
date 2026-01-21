<?php
require_once __DIR__ . '/bootstrap.php';

try {
    $baglanti->exec('ALTER TABLE dergi_tanim CHANGE onay is_approved TINYINT DEFAULT 0');
    echo "Column 'onay' renamed to 'is_approved' successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
