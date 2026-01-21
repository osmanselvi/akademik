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
}
