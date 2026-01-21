<?php
namespace App\Models;

class MakaleHakem extends BaseModel {
    protected $table = 'makale_hakem';
    
    // Status Constants
    const STATUS_PENDING = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_COMPLETED = 3;

    /**
     * Get assignments by reviewer ID
     */
    public function getByReviewer($reviewerId) {
        $sql = "SELECT mh.*, gm.makale_adi, gm.dosya, gm.created_at as submitted_at 
                FROM {$this->table} mh 
                JOIN gonderilen_makale gm ON mh.makale_id = gm.id 
                WHERE mh.hakem_id = :reviewer_id 
                ORDER BY mh.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['reviewer_id' => $reviewerId]);
        return $stmt->fetchAll();
    }

    /**
     * Get assignments by article ID
     */
    public function getByArticle($articleId) {
        $sql = "SELECT mh.*, y.ad_soyad as hakem_adi, y.email 
                FROM {$this->table} mh 
                JOIN yonetim y ON mh.hakem_id = y.id 
                WHERE mh.makale_id = :article_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    /**
     * Check if reviewer is already assigned
     */
    public function isAssigned($articleId, $reviewerId) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE makale_id = :aid AND hakem_id = :hid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['aid' => $articleId, 'hid' => $reviewerId]);
        return $stmt->fetchColumn() > 0;
    }
}
