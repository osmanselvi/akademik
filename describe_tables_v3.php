<?php
require 'bootstrap.php';
$db = getDatabase();
$output = "";
foreach(['uye', 'yorum', 'gonderilen_makale'] as $table) {
    $output .= "--- $table ---\n";
    $stmt = $db->query("DESCRIBE $table");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= print_r($row, true);
    }
}
file_put_contents('D:/php_site/ebp/ydergi/table_descriptions.txt', $output);
echo "File created at D:/php_site/ebp/ydergi/table_descriptions.txt";
