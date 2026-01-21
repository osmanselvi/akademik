<?php
require_once __DIR__ . '/../bootstrap.php';
$pdo = $baglanti;

try {
    $pdo->beginTransaction();

    // 1. Fix orphan grup_id values (point to group 5 - Üye)
    $updateSql = "UPDATE yonetim y 
                  LEFT JOIN usergroup ug ON y.grup_id = ug.id 
                  SET y.grup_id = 5 
                  WHERE ug.id IS NULL";
    $count = $pdo->exec($updateSql);
    echo "Updated $count orphan records to default group 5.\n";

    // 2. Add Foreign Key constraint
    // Note: We need to make sure grup_id is the same type as usergroup.id (usually int)
    $fkSql = "ALTER TABLE yonetim 
              ADD CONSTRAINT fk_yonetim_grup 
              FOREIGN KEY (grup_id) REFERENCES usergroup(id) 
              ON DELETE RESTRICT ON UPDATE CASCADE";
    
    $pdo->exec($fkSql);
    echo "Foreign Key 'fk_yonetim_grup' added successfully.\n";

    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage() . "\n";
}
?>
