<?php
namespace App\Models;

/**
 * YayinKurul Model
 * Handles board members and their associations
 */
class YayinKurul extends BaseModel {
    protected $table = 'yayin_kurul';
    protected $timestamps = false; // Using manually in create for created_at
    protected $softDeletes = false;

    /**
     * Get members with related info (unvan, kurul, gorev)
     */
    public function getWithRelations($filters = []) {
        $sql = "SELECT yk.*, u.unvan as unvan_text, k.kurul as kurul_text, kg.kurul_gorev as gorev_text,
                       adm.ad_soyad as creator_name
                FROM {$this->table} yk
                LEFT JOIN unvan u ON yk.unvan = u.id
                LEFT JOIN kurul_gorev kg ON yk.kurul_gorev = kg.id
                LEFT JOIN kurul k ON kg.kurul = k.id
                LEFT JOIN yonetim adm ON yk.created_by = adm.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['kurul_id'])) {
            $sql .= " AND kg.kurul = ?";
            $params[] = $filters['kurul_id'];
        }

        if (isset($filters['is_approved']) && $filters['is_approved'] !== '') {
            $sql .= " AND yk.is_approved = ?";
            $params[] = $filters['is_approved'];
        }

        // Sort logic: Ranked items (sira > 0) come first, ordered by sira ASC.
        // Unranked items (sira = 0 or NULL) come last, ordered by id DESC.
        $sql .= " ORDER BY (yk.sira IS NULL OR yk.sira = 0) ASC, yk.sira ASC, yk.id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Find member with relations
     */
    public function findWithRelations($id) {
        $sql = "SELECT yk.*, u.unvan as unvan_text, kg.kurul as kurul_id, kg.kurul_gorev as gorev_text
                FROM {$this->table} yk
                LEFT JOIN unvan u ON yk.unvan = u.id
                LEFT JOIN kurul_gorev kg ON yk.kurul_gorev = kg.id
                WHERE yk.id = ?
                LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
