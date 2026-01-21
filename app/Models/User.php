<?php
namespace App\Models;

/**
 * User Model (yonetim tablosu)
 */
class User extends BaseModel {
    protected $table = 'yonetim';
    protected $primaryKey = 'id';
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    
    /**
     * Create user with modern Bcrypt password
     */
    public function createUser($data) {
        if (isset($data['password'])) {
            $data['sifre'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        
        return $this->create($data);
    }
    
    /**
     * Verify password using Bcrypt (with MD5 fallback and auto-upgrade)
     */
    public function verifyPassword($user, $password) {
        // 1. Try modern password_verify (Bcrypt/Argon2)
        if (password_verify($password, $user->sifre)) {
            return true;
        }

        // 2. Fallback to old MD5 if hash is 32 chars (standard MD5)
        if (strlen($user->sifre) === 32 && md5($password) === $user->sifre) {
            // Success! We should upgrade this user's password to Bcrypt now
            $this->update($user->id, [
                'sifre' => password_hash($password, PASSWORD_BCRYPT)
            ]);
            return true;
        }

        return false;
    }
    
    /**
     * Check if user has role
     */
    public function hasRole($userId, $roleId) {
        $user = $this->find($userId);
        return $user && $user->grup_id == $roleId;
    }
    
    /**
     * Get user's full name
     */
    public function getFullName($user) {
        return $user->ad_soyad ?? 'Bilinmeyen Kullanıcı';
    }

    /**
     * Get user's group info
     */
    public function getGroup($userId) {
        $sql = "SELECT ug.* 
                FROM usergroup ug
                JOIN {$this->table} y ON y.grup_id = ug.id
                WHERE y.id = ?
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    /**
     * Get users with group info (Paginated)
     */
    public function paginateWithGroups($page = 1, $perPage = 10) {
        $page = (int)$page;
        $perPage = (int)$perPage;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT u.*, g.grupadi as grup_adi 
                FROM {$this->table} u
                LEFT JOIN usergroup g ON u.grup_id = g.id
                ORDER BY u.id DESC
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
     * Get reviewers (Hakem group = 4)
     */
    public function getReviewers() {
        $sql = "SELECT id, ad_soyad FROM {$this->table} WHERE grup_id = 4 ORDER BY ad_soyad ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
