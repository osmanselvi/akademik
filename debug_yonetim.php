<?php
require_once __DIR__ . '/bootstrap.php';
$pdo = $baglanti;
$cols = $pdo->query('DESCRIBE yonetim')->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $c) {
    echo $c['Field'] . " (" . $c['Type'] . ")\n";
}
?>
