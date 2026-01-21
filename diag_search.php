<?php
$host = 'localhost';
$db = 'ebp_server';
$user = 'ebp_user';
$pass = '99882580';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    echo "=== ONLINE_MAKALE SAMPLES ===\n";
    $data = $pdo->query("SELECT id, makale_baslik, dergi_tanim FROM online_makale LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $r) {
        echo "ID: {$r['id']} | Title: {$r['makale_baslik']} | Dergi Link: {$r['dergi_tanim']}\n";
    }
    
    echo "\n=== DERGI_TANIM SAMPLES ===\n";
    $data = $pdo->query("SELECT id, dergi_tanim, yayin_created_at FROM dergi_tanim")->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $r) {
        $year = $r['yayin_created_at'] ? date('Y', strtotime($r['yayin_created_at'])) : 'NULL';
        echo "ID: {$r['id']} | Title: {$r['dergi_tanim']} | Year: {$year}\n";
    }

} catch (PDOException $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}
