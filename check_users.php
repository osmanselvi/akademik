<?php
require_once __DIR__ . '/bootstrap.php';
$pdo = $baglanti;
$users = $pdo->query('SELECT id, ad_soyad, kullanicisifre, hashpass, email FROM yonetim LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
print_r($users);
?>
