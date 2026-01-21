<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);


$output = "=== DERGI_KUNYE_BASLIK ===\n";
try {
    $stmt = $pdo->query('DESCRIBE dergi_kunye_baslik');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= "{$row['Field']} ({$row['Type']})\n";
    }
} catch (Exception $e) {
    $output .= "Tablo bulunamadı: " . $e->getMessage() . "\n";
}

$output .= "\n=== DERGI_KUNYE ===\n";
try {
    $stmt = $pdo->query('DESCRIBE dergi_kunye');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= "{$row['Field']} ({$row['Type']})\n";
    }
} catch (Exception $e) {
    $output .= "Tablo bulunamadı: " . $e->getMessage() . "\n";
}

$output .= "\n=== Örnek Veriler ===\n";
try {
    $output .= "\nBaşlıklar:\n";
    $stmt = $pdo->query('SELECT * FROM dergi_kunye_baslik');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
    
    $output .= "\nKünye Kayıtları:\n";
    $stmt = $pdo->query('SELECT * FROM dergi_kunye LIMIT 10');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (Exception $e) {
    $output .= "Veri okunamadı: " . $e->getMessage() . "\n";
}

file_put_contents('kunye_structure.txt', $output);
echo "Sonuçlar kunye_structure.txt dosyasına kaydedildi.\n";
