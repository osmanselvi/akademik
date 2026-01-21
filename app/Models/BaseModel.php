<?php
namespace App\Models;

use PDO;

/**
 * Base Model Class
 * 
 * All models should extend this class
 */
abstract class BaseModel {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';
    protected $timestamps = false;
    protected $softDeletes = false;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all records
     */
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($this->softDeletes) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        
        $sql .= " ORDER BY {$this->primaryKey} DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Find record by ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        
        if ($this->softDeletes) {
            $sql .= " AND deleted_at IS NULL";
        }
        
        $sql .= " LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get records with a simple where clause
     */
    public function where($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        
        if ($this->softDeletes) {
            $sql .= " AND deleted_at IS NULL";
        }
        
        $sql .= " ORDER BY {$this->primaryKey} DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Create new record
     */
    public function create($data) {
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Update record
     */
    public function update($id, $data) {
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $sets = [];
        $values = [];
        
        foreach ($data as $field => $value) {
            $sets[] = "{$field} = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($values);
    }
    
    /**
     * Delete record (soft or hard delete)
     */
    public function delete($id) {
        if ($this->softDeletes) {
            $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE {$this->primaryKey} = ?";
        } else {
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        }
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Restore soft deleted record
     */
    public function restore($id) {
        if (!$this->softDeletes) {
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET deleted_at = NULL WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get records with pagination
     */
    public function paginate($page = 1, $perPage = null) {
        $perPage = (int)($perPage ?? config('app.pagination.per_page'));
        $page = (int)$page;
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        
        if ($this->softDeletes) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        
        // LIMIT and OFFSET should be integers for security
        $sql .= " ORDER BY {$this->primaryKey} DESC LIMIT {$perPage} OFFSET {$offset}";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Get total count
        $countSql = "SELECT COUNT(*) FROM {$this->table}";
        if ($this->softDeletes) {
            $countSql .= " WHERE deleted_at IS NULL";
        }
        
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
     * Execute custom query
     */
    protected function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
