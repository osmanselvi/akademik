<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

require_once __DIR__ . '/app/models/BaseModel.php';
require_once __DIR__ . '/app/models/DergiKunye.php';

$kunyeModel = new \App\Models\DergiKunye($pdo);
$kunye = $kunyeModel->getApprovedGrouped();

echo "=== KÜNYE VERİLERİ (GROUPED) ===\n\n";
foreach($kunye as $kategori => $entries) {
    echo "[$kategori]\n";
    foreach($entries as $entry) {
        echo "  - {$entry->ad_soyad}\n";
    }
    echo "\n";
}

echo "\n=== FOOTER İÇİN KONTROL ===\n";
echo "Kurucusu var mı? " . (isset($kunye['Kurucusu']) ? 'EVET' : 'HAYIR') . "\n";
echo "İmtiyaz Sahibi var mı? " . (isset($kunye['İmtiyaz Sahibi']) ? 'EVET' : 'HAYIR') . "\n";
echo "Yazı İşleri Müdürü var mı? " . (isset($kunye['Yazı İşleri Müdürü']) ? 'EVET' : 'HAYIR') . "\n";
echo "Editör var mı? " . (isset($kunye['Editör']) ? 'EVET' : 'HAYIR') . "\n";
