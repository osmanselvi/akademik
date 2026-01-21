<?php
require_once __DIR__ . '/bootstrap.php';

echo "=== DERGI LISTESI ===\n\n";

$stmt = $baglanti->query('SELECT id, dergi_tanim, is_approved, dergi_kapak FROM dergi_tanim ORDER BY id DESC');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $status = $row['is_approved'] == 1 ? '[GÜNCEL]' : '[GEÇMİŞ]';
    echo $row['id'] . ' - ' . $row['dergi_tanim'] . ' ' . $status . ' - kapak: ' . ($row['dergi_kapak'] ?: 'YOK') . "\n";
}
