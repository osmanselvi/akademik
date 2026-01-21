<?php
require_once __DIR__ . '/bootstrap.php';
$pdo = $baglanti;

// Check for orphans
$sql = "SELECT y.id, y.email, y.grup_id 
        FROM yonetim y 
        LEFT JOIN usergroup ug ON y.grup_id = ug.id 
        WHERE ug.id IS NULL";
$orphans = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

if (empty($orphans)) {
    echo "No orphan records found. Safe to add Foreign Key.\n";
} else {
    echo "Orphan records found (grup_id not in usergroup):\n";
    foreach ($orphans as $o) {
        echo "User ID: {$o['id']} - Email: {$o['email']} - Invalid group_id: {$o['grup_id']}\n";
    }
}
?>
