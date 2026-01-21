<?php
namespace App\Models;

/**
 * KurulGorev Model
 * Handles duties within boards (Baş Editör, Sayı Editörü, etc.)
 */
class KurulGorev extends BaseModel {
    protected $table = 'kurul_gorev';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get approved duties for a specific board
     */
    public function getByKurul($kurulId) {
        $sql = "SELECT * FROM {$this->table} WHERE kurul = ? AND is_approved = 1 ORDER BY kurul_gorev ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$kurulId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all approved duties
     */
    public function getApproved() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 ORDER BY kurul_gorev ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
