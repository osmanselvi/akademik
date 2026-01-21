<?php
namespace App\Models;

/**
 * Dergi Model
 */
class Dergi extends BaseModel {
    protected $table = 'dergi_tanim';
    protected $softDeletes = false; // Database'de deleted_at kolonu yok
    
    /**
     * Get current published issue
     */
    public function getCurrent() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    
    /**
     * Get archive (past issues)
     */
    public function getArchive() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE is_approved = 0 
                ORDER BY yayin_created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    /**
     * Get issue with article count
     */
    public function withArticleCount($dergiId) {
        $sql = "SELECT d.*, COUNT(m.id) as makale_sayisi
                FROM dergi_tanim d
                LEFT JOIN online_makale m ON d.id = m.dergi_tanim AND m.is_approved = 1 AND m.dosya IS NOT NULL AND m.dosya != ''
                WHERE d.id = ?
                GROUP BY d.id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dergiId]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    
    /**
     * Get all issues with article counts
     */
    public function allWithArticleCounts() {
        $sql = "SELECT d.*, COUNT(m.id) as makale_sayisi
                FROM dergi_tanim d
                LEFT JOIN online_makale m ON d.id = m.dergi_tanim AND m.is_approved = 1 AND m.dosya IS NOT NULL AND m.dosya != ''
                GROUP BY d.id
                ORDER BY d.id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all issues with article counts (Paginated)
     */
    public function paginateWithArticleCounts($page = 1, $perPage = 9) {
        $page = (int)$page;
        $perPage = (int)$perPage;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT d.*, COUNT(m.id) as makale_sayisi
                FROM dergi_tanim d
                LEFT JOIN online_makale m ON d.id = m.dergi_tanim AND m.is_approved = 1 AND m.dosya IS NOT NULL AND m.dosya != ''
                GROUP BY d.id
                ORDER BY d.id DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_OBJ);

        // Get total count
        $countSql = "SELECT COUNT(*) FROM {$this->table}";
        $countStmt = $this->pdo->prepare($countSql);
        $countStmt->execute();
        $total = $countStmt->fetchColumn();

        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
    /**
     * Delete journal and all related articles and files (Cascade Delete)
     */
    public function deleteWithArticles($id) {
        $dergi = $this->find($id);
        if (!$dergi) return false;

        $makaleModel = new Makale($this->pdo);
        $makaleler = $makaleModel->getByDergi($id);

        // Delete article files and records
        foreach ($makaleler as $makale) {
            if (!empty($makale->dosya)) {
                $filePath = publicPath('makaleler/' . $makale->dosya);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            $makaleModel->delete($makale->id);
        }

        // Delete journal files
        if (!empty($dergi->jenerikdosyasi)) {
            $jenerikPath = publicPath('makaleler/' . $dergi->jenerikdosyasi);
            if (file_exists($jenerikPath)) {
                @unlink($jenerikPath);
            }
        }
        if (!empty($dergi->dergi_kapak)) {
            $kapakPath = publicPath('images/' . $dergi->dergi_kapak);
            if (file_exists($kapakPath)) {
                @unlink($kapakPath);
            }
        }

        // Delete journal record
        return $this->delete($id);
    }
    /**
     * Get all journal issues
     */
    public function all() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get distinct publication years
     */
    public function getDistinctYears() {
        $sql = "SELECT DISTINCT YEAR(yayin_created_at) as yil 
                FROM {$this->table} 
                WHERE yayin_created_at IS NOT NULL 
                ORDER BY yil DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}

