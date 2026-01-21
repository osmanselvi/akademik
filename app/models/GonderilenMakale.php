<?php
namespace App\Models;

class GonderilenMakale extends BaseModel {
    protected $table = 'gonderilen_makale';

    /**
     * Get human readable status
     */
    public function getStatusLabel($status = null) {
        $status = $status ?? $this->status;
        $labels = [
            0 => ['text' => 'Yeni Gönderi', 'class' => 'bg-info'],
            1 => ['text' => 'Düzeltme Talep Edildi', 'class' => 'bg-warning'],
            2 => ['text' => 'Düzeltme Gönderildi', 'class' => 'bg-primary'],
            3 => ['text' => 'Onaylandı', 'class' => 'bg-success'],
            4 => ['text' => 'Reddedildi', 'class' => 'bg-danger']
        ];
        return $labels[$status] ?? ['text' => 'Bilinmiyor', 'class' => 'bg-secondary'];
    }

    /**
     * Get revisions for a submission
     */
    public function getRevisions($id) {
        $sql = "SELECT r.*, y.ad_soyad as sender_name 
                FROM makale_revizyon_notlari r
                LEFT JOIN yonetim y ON r.sender_id = y.id
                WHERE r.makale_id = ? 
                ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
