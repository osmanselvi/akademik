<?php
namespace App\Models;

/**
 * Dizin Model
 * Handles indexing/database information
 */
class Dizin extends BaseModel {
    protected $table = 'dizin';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get all approved dizinler
     */
    public function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 ORDER BY dizin_adi ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    /**
     * Get filtered dizinler
     */
    public function getFiltered($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (isset($filters['is_approved']) && $filters['is_approved'] !== '') {
            $sql .= " AND is_approved = ?";
            $params[] = $filters['is_approved'];
        }
        
        $sql .= " ORDER BY dizin_adi ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
