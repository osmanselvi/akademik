<?php
namespace App\Models;

/**
 * EmailTemplate Model
 */
class EmailTemplate extends BaseModel {
    protected $table = 'email_templates';
    
    /**
     * Find template by code
     */
    public function findByCode($code) {
        $sql = "SELECT * FROM {$this->table} WHERE code = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
