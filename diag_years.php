<?php
$host = 'localhost';
$db = 'ebp_server';
$user = 'ebp_user';
$pass = '99882580';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $data = $pdo->query("SELECT id, dergi_tanim, yayin_created_at, is_approved FROM dergi_tanim")->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $r) {
        $year = $r['yayin_created_at'] ? date('Y', strtotime($r['yayin_created_at'])) : 'NULL';
        echo "ID: {$r['id']} | Title: {$r['dergi_tanim']} | Year: {$year} | Raw Date: {$r['yayin_created_at']} | Approved: {$r['is_approved']}\n";
    }
} catch (PDOException $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}
