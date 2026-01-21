<?php
require_once __DIR__ . '/bootstrap.php';
$pdo = $baglanti;

$res = $pdo->query("SHOW CREATE TABLE yonetim")->fetch(PDO::FETCH_ASSOC);
echo $res['Create Table'];
?>
