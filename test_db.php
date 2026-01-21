<?php
$host = 'localhost';
$db = 'ebp_server';
$user = 'ebp_user';
$pass = '99882580';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    echo "=== TABLOLAR ===\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach($tables as $t) {
        echo "- $t\n";
    }
    
    echo "\n=== DERGI_TANIM YAPISI ===\n";
    $cols = $pdo->query("DESCRIBE dergi_tanim")->fetchAll(PDO::FETCH_ASSOC);
    foreach($cols as $c) {
        echo $c['Field'] . " (" . $c['Type'] . ")\n";
    }
    
    echo "\n=== DERGI_TANIM VERISI ===\n";
    $data = $pdo->query("SELECT * FROM dergi_tanim ORDER BY 1 DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $row) {
        print_r($row);
    }
    
} catch (PDOException $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}
