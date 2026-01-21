<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$output = "=== MAKALE_SOZLESME TABLE ===\n";
try {
    $stmt = $pdo->query('DESCRIBE makale_sozlesme');
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $output .= "{$row['Field']} ({$row['Type']})\n";
    }
    
    $output .= "\n=== Sample Data ===\n";
    $stmt = $pdo->query('SELECT * FROM makale_sozlesme LIMIT 1');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $output .= json_encode($row, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    }
} catch (Exception $e) {
    $output .= "Error: " . $e->getMessage() . "\n";
}

file_put_contents('makale_sozlesme_structure.txt', $output);
echo "Saved to makale_sozlesme_structure.txt\n";
