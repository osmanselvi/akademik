<?php
namespace App\Models;

/**
 * DergiKunye Model
 * Handles masthead entries
 */
class DergiKunye extends BaseModel {
    protected $table = 'dergi_kunye';
    protected $timestamps = false;
    protected $softDeletes = false;

    /**
     * Get all entries with their category names
     */
    public function getWithCategories($filters = []) {
        $sql = "SELECT 
                    dk.*,
                    dkb.baslik as kategori
                FROM {$this->table} dk
                INNER JOIN dergi_kunye_baslik dkb ON dkb.id = dk.baslik_id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['baslik_id'])) {
            $sql .= " AND dk.baslik_id = ?";
            $params[] = $filters['baslik_id'];
        }
        
        if (isset($filters['is_approved']) && $filters['is_approved'] !== '') {
            $sql .= " AND dk.is_approved = ?";
            $params[] = $filters['is_approved'];
        }
        
        $sql .= " ORDER BY dkb.id ASC, dk.id ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get approved entries grouped by category
     */
    public function getApprovedGrouped() {
        $sql = "SELECT 
                    dk.*,
                    dkb.baslik as kategori,
                    dkb.id as kategori_id
                FROM {$this->table} dk
                INNER JOIN dergi_kunye_baslik dkb ON dkb.id = dk.baslik_id
                WHERE dk.is_approved = 1 AND dkb.is_approved = 1
                ORDER BY dkb.id ASC, dk.id ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_OBJ);
        
        // Group by category
        $grouped = [];
        foreach ($results as $row) {
            if (!isset($grouped[$row->kategori])) {
                $grouped[$row->kategori] = [];
            }
            $grouped[$row->kategori][] = $row;
        }
        
        return $grouped;
    }
}
