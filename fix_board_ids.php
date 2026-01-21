<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

echo "Kurul ID'lerini düzenliyorum...\n\n";

// Yayın Kurulu'nu ID 4'ten ID 2'ye taşı
$pdo->exec("UPDATE kurul SET id = 2 WHERE id = 4");
echo "✓ Yayın Kurulu ID 4 → 2\n";

// kurul_gorev tablosundaki referansları güncelle
$pdo->exec("UPDATE kurul_gorev SET kurul = 2 WHERE kurul = 4");
echo "✓ kurul_gorev referansları güncellendi\n";

// yayin_kurul tablosundaki kurul_gorev referanslarını kontrol et
// (Bu tablo kurul_gorev.id'yi tutuyor, kurul.id'yi değil, bu yüzden değişiklik gerekmez)

echo "\nYeni yapı:\n";
echo "ID 1: Editörler Kurulu\n";
echo "ID 2: Yayın Kurulu\n";
echo "ID 3: Danışma Kurulu\n";

echo "\nDoğrulama yapılıyor...\n";
$stmt = $pdo->query('SELECT * FROM kurul ORDER BY id');
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "ID: {$row['id']} - {$row['kurul']}\n";
}
