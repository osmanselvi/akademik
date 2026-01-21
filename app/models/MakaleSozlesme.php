<?php
namespace App\Models;

/**
 * MakaleSozlesme Model
 * Handles copyright agreement terms
 */
class MakaleSozlesme extends BaseModel {
    protected $table = 'makale_sozlesme';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get all approved agreement terms grouped by title
     */
    public function getGroupedApproved() {
        $sql = "SELECT id, baslik, metin FROM {$this->table} WHERE is_approved = 1 ORDER BY id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_OBJ);
        
        $grouped = [];
        foreach ($results as $item) {
            $grouped[$item->baslik][] = $item;
        }
        
        return $grouped;
    }
}
