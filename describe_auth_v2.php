<?php
require 'bootstrap.php';
$db = getDatabase();
$output = "";
foreach(['yonetim', 'usergroup'] as $table) {
    $output .= "--- $table ---\n";
    $stmt = $db->query("DESCRIBE $table");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= print_r($row, true);
    }
}
file_put_contents('auth_descriptions.txt', $output);
echo "Done";
