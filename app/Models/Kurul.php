<?php
namespace App\Models;

/**
 * Kurul Model
 * Handles journal boards (Editör Kurulu, Danışma Kurulu, etc.)
 */
class Kurul extends BaseModel {
    protected $table = 'kurul';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get all approved boards
     */
    public function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 ORDER BY id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
