<?php
namespace App\Models;

/**
 * DergiKunyeBaslik Model
 * Handles masthead categories (Kurucusu, İmtiyaz Sahibi, etc.)
 */
class DergiKunyeBaslik extends BaseModel {
    protected $table = 'dergi_kunye_baslik';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get all approved categories
     */
    public function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 ORDER BY id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
