<?php
namespace App\Models;

/**
 * Makale Model
 */
class Makale extends BaseModel {
    protected $table = 'online_makale';
    protected $softDeletes = false; // Database'de deleted_at kolonu yok
    
    /**
     * Get articles by journal issue
     */
    public function getByDergi($dergiId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE dergi_tanim = ? AND is_approved = 1 AND dosya IS NOT NULL AND dosya != ''
                ORDER BY id ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dergiId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    /**
     * Search articles with optional filters
     */
    public function search($query, $filters = []) {
        $searchTerm = "%{$query}%";
        $params = [$searchTerm, $searchTerm, $searchTerm];
        
        $sql = "SELECT m.*, d.dergi_tanim as dergi_adi, YEAR(d.yayin_created_at) as yayin_yili
                FROM {$this->table} m
                LEFT JOIN dergi_tanim d ON m.dergi_tanim = d.id
                WHERE (m.makale_baslik LIKE ? OR m.makale_yazar LIKE ? OR m.anahtar_kelime LIKE ?)";
        
        if (!empty($filters['start_year']) && !empty($filters['end_year'])) {
            $sql .= " AND YEAR(d.yayin_created_at) BETWEEN ? AND ?";
            $params[] = $filters['start_year'];
            $params[] = $filters['end_year'];
        } elseif (!empty($filters['start_year'])) {
            $sql .= " AND YEAR(d.yayin_created_at) >= ?";
            $params[] = $filters['start_year'];
        } elseif (!empty($filters['end_year'])) {
            $sql .= " AND YEAR(d.yayin_created_at) <= ?";
            $params[] = $filters['end_year'];
        }
        
        if (!empty($filters['journal_id'])) {
            $sql .= " AND d.id = ?";
            $params[] = $filters['journal_id'];
        }
        
        $sql .= " ORDER BY m.id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    /**
     * Get article with journal info
     */
    public function findWithJournal($id) {
        $sql = "SELECT m.*, d.id as dergi_tanim_id, d.dergi_tanim as dergi_tanim_text, d.yayin_created_at
                FROM {$this->table} m
                LEFT JOIN dergi_tanim d ON m.dergi_tanim = d.id
                WHERE m.id = ?
                LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    /**
     * Increment view count
     */
    public function incrementView($id) {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Increment download count
     */
    public function incrementDownload($id) {
        $sql = "UPDATE {$this->table} SET download_count = download_count + 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
