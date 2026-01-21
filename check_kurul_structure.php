<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

echo "=== KURUL TABLE ===\n";
$stmt = $pdo->query('SELECT * FROM kurul ORDER BY id');
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "ID: {$row['id']} - {$row['kurul']} (Approved: {$row['is_approved']})\n";
}

echo "\n=== KURUL_GOREV TABLE ===\n";
try {
    $stmt = $pdo->query('SELECT id, kurul, kurul_gorev, is_approved FROM kurul_gorev ORDER BY kurul, id');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo sprintf("ID: %-3d | Kurul: %d | Görev: %-30s | Onay: %d\n", 
            $row['id'], 
            $row['kurul'], 
            $row['kurul_gorev'],
            $row['is_approved']
        );
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== YAYIN_KURUL SAMPLE (First 5) ===\n";
try {
    $stmt = $pdo->query('SELECT id, ad_soyad, kurul_gorev, is_approved FROM yayin_kurul LIMIT 5');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo sprintf("ID: %-3d | Ad: %-30s | Görev ID: %-3d | Onay: %d\n",
            $row['id'],
            $row['ad_soyad'],
            $row['kurul_gorev'],
            $row['is_approved']
        );
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
