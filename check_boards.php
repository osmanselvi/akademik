<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);


$output = "KURUL TABLOSU:\n";
$output .= str_repeat("=", 50) . "\n";
$stmt = $pdo->query('SELECT * FROM kurul ORDER BY id');
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $output .= "ID: {$row['id']} - {$row['kurul']}\n";
}

$output .= "\n\nKURUL_GOREV TABLOSU (Kurul ID'ye göre):\n";
$output .= str_repeat("=", 50) . "\n";
$stmt = $pdo->query('SELECT id, kurul, kurul_gorev FROM kurul_gorev ORDER BY kurul, id');
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $output .= "Görev ID: {$row['id']} | Kurul: {$row['kurul']} | {$row['kurul_gorev']}\n";
}

file_put_contents('board_structure.txt', $output);
echo "Sonuçlar board_structure.txt dosyasına kaydedildi.\n";
