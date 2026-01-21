<?php
namespace App\Models;

/**
 * DestekTalep Model
 */
class DestekTalep extends BaseModel {
    protected $table = 'destek_talepleri';
    protected $timestamps = true;

    /**
     * Get human readable status label
     */
    public function getStatusLabel($status) {
        switch ($status) {
            case 0:
                return ['text' => 'Açık', 'class' => 'bg-info'];
            case 1:
                return ['text' => 'Yanıtlandı', 'class' => 'bg-success'];
            default:
                return ['text' => 'Bilinmiyor', 'class' => 'bg-secondary'];
        }
    }

    /**
     * Get all support requests with user names for admin
     */
    public function getAllWithUsers() {
        $sql = "SELECT dt.*, y.ad_soyad as researcher_name, y.email as researcher_email 
                FROM {$this->table} dt
                LEFT JOIN yonetim y ON dt.user_id = y.id
                ORDER BY dt.status ASC, dt.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
