<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8mb4",
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $tables = ['dergi_tanim', 'online_makale', 'gonderilen_makale', 'makale_tur'];
    foreach ($tables as $table) {
        echo "=== TABLE: $table ===\n";
        try {
            $stmt = $pdo->query("DESCRIBE $table");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                echo "{$row['Field']} - {$row['Type']}\n";
            }
            
            echo "\nSample Data:\n";
            $stmt = $pdo->query("SELECT * FROM $table LIMIT 1");
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                print_r($data);
            } else {
                echo "No data found.\n";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
