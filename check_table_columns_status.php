<?php
require 'vendor/autoload.php';
$pdo = new PDO('mysql:host=localhost;dbname=ebp_db', 'root', '');

$stmt = $pdo->query("SHOW COLUMNS FROM gonderilen_makale");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Columns in gonderilen_makale:\n";
foreach ($columns as $col) {
    echo $col['Field'] . "\n";
}
