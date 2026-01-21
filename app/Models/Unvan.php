<?php
namespace App\Models;

/**
 * Unvan Model
 * Handles academic titles (Prof. Dr., Doç. Dr., etc.)
 */
class Unvan extends BaseModel {
    protected $table = 'unvan';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get all approved titles
     */
    public function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 ORDER BY unvan ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
