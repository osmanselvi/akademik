<?php
require_once __DIR__ . '/bootstrap.php';
$pdo = $baglanti;

echo "--- usergroup columns ---\n";
try {
    $cols = $pdo->query('DESCRIBE usergroup')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) {
        echo $c['Field'] . " (" . $c['Type'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error describing usergroup: " . $e->getMessage() . "\n";
}

echo "\n--- usergroup data ---\n";
try {
    $data = $pdo->query('SELECT id, grupadi FROM usergroup')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($data as $d) {
        echo "ID: {$d['id']} - Name: {$d['grupadi']}\n";
    }
} catch (Exception $e) {
    echo "Error fetching usergroup data: " . $e->getMessage() . "\n";
}
?>
