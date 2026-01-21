<?php
require 'bootstrap.php';
$db = getDatabase();
foreach(['yonetim', 'usergroup'] as $table) {
    echo "--- $table ---\n";
    $stmt = $db->query("DESCRIBE $table");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode($row) . PHP_EOL;
    }
}
