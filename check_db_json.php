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

    $results = [];
    $tables = ['dergi_tanim', 'online_makale', 'gonderilen_makale', 'makale_tur'];
    foreach ($tables as $table) {
        $tableInfo = ['name' => $table, 'columns' => [], 'sample' => null];
        try {
            $stmt = $pdo->query("DESCRIBE $table");
            $tableInfo['columns'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt = $pdo->query("SELECT * FROM $table LIMIT 1");
            $tableInfo['sample'] = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $tableInfo['error'] = $e->getMessage();
        }
        $results[] = $tableInfo;
    }
    
    file_put_contents('db_schema_details.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "Schema details saved to db_schema_details.json\n";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
