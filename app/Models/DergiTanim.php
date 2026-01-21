<?php
namespace App\Models;

/**
 * DergiTanim Model
 * Handles journal issues
 */
class DergiTanim extends BaseModel {
    protected $table = 'dergi_tanim';
    protected $timestamps = false; // Using custom date fields

    /**
     * Get all approved journal issues
     */
    public function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
