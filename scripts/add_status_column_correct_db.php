<?php
require 'vendor/autoload.php';

// Hardcoded to match .env based on inspection
$host = 'localhost';
$db   = 'ebp_server';
$user = 'ebp_user';
$pass = '99882580';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected to $db successfully.\n";

    echo "Checking schema for 'gonderilen_makale'...\n";

    // Check if status column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM gonderilen_makale LIKE 'status'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE gonderilen_makale ADD COLUMN status TINYINT DEFAULT 0");
        echo "Added 'status' column to 'gonderilen_makale' table.\n";
    } else {
        echo "'status' column already exists in 'gonderilen_makale' table.\n";
    }

} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
