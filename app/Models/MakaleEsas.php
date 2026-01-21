<?php
namespace App\Models;

/**
 * MakaleEsas Model
 * Handles article guidelines/principles
 */
class MakaleEsas extends BaseModel {
    protected $table = 'makale_esas';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get all approved guidelines
     */
    public function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 ORDER BY id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    /**
     * Get specific guideline by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND is_approved = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
