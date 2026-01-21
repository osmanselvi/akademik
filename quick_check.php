<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$pdo = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
$stmt = $pdo->query('SELECT id, kurul FROM kurul ORDER BY id');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' - ' . $row['kurul'] . PHP_EOL;
}
