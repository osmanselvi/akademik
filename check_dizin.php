<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$output = "=== DIZIN TABLE STRUCTURE ===\n";
try {
    $stmt = $pdo->query('DESCRIBE dizin');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= "{$row['Field']} ({$row['Type']})\n";
    }
} catch (Exception $e) {
    $output .= "Tablo bulunamadı: " . $e->getMessage() . "\n";
}

$output .= "\n=== Sample Data ===\n";
try {
    $stmt = $pdo->query('SELECT * FROM dizin LIMIT 5');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (Exception $e) {
    $output .= "Veri okunamadı: " . $e->getMessage() . "\n";
}

file_put_contents('dizin_structure.txt', $output);
echo "Sonuçlar dizin_structure.txt dosyasına kaydedildi.\n";
