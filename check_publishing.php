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

    function checkTable($pdo, $tableName) {
        echo "\n=== TABLE: $tableName ===\n";
        try {
            $stmt = $pdo->query("DESCRIBE $tableName");
            print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            echo "\nSample Data:\n";
            $stmt = $pdo->query("SELECT * FROM $tableName LIMIT 1");
            print_r($stmt->fetch(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    checkTable($pdo, 'dergi_tanim');
    checkTable($pdo, 'makale');
    checkTable($pdo, 'gonderilen_makale');
    checkTable($pdo, 'makale_tur');

} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
