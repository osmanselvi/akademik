<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$output = "=== MAKALE_ESAS TABLE ===\n";
try {
    $stmt = $pdo->query('DESCRIBE makale_esas');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= "{$row['Field']} ({$row['Type']})\n";
    }
    
    $output .= "\n=== Sample Data ===\n";
    $stmt = $pdo->query('SELECT * FROM makale_esas LIMIT 3');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (Exception $e) {
    $output .= "Error: " . $e->getMessage() . "\n";
}

file_put_contents('makale_esas_structure.txt', $output);
echo "Saved to makale_esas_structure.txt\n";
