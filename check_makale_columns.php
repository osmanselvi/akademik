<?php
require_once __DIR__ . '/bootstrap.php';
$pdo = $baglanti;
$cols = $pdo->query('SHOW COLUMNS FROM online_makale')->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $cols) . "\n";
?>
