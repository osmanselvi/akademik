<?php
namespace App\Models;

/**
 * OnlineMakale Model
 * Handles published articles
 */
class OnlineMakale extends BaseModel {
    protected $table = 'online_makale';
    protected $timestamps = false;

    /**
     * Get articles with their types for a specific issue
     */
    public function getByIssue($issueId) {
        $sql = "SELECT om.*, mt.makale_turu as tur_adi 
                FROM {$this->table} om
                LEFT JOIN makale_tur mt ON om.makale_turu = mt.id
                WHERE om.dergi_tanim = ? AND om.is_approved = 1
                ORDER BY om.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$issueId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
