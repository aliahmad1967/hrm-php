<?php
/**
 * Base Model Class
 */

namespace App\Models;

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $timestamps = true;
    
    public function __construct() {
        $this->db = \Database::getInstance();
    }
    
    /**
     * Find record by ID
     */
    public function find($id) {
        return $this->db->query(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        )->fetch();
    }
    
    /**
     * Get all records
     */
    public function all($orderBy = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        return $this->db->query($sql)->fetchAll();
    }
    
    /**
     * Create new record
     */
    public function create($data) {
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip($this->fillable));
        
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = sprintf(
            "INSERT INTO {$this->table} (%s) VALUES (%s)",
            implode(', ', $fields),
            implode(', ', $placeholders)
        );
        
        $this->db->query($sql, $values);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record
     */
    public function update($id, $data) {
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip($this->fillable));
        
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        
        $sql = sprintf(
            "UPDATE {$this->table} SET %s WHERE {$this->primaryKey} = ?",
            implode(', ', $fields)
        );
        
        $this->db->query($sql, $values);
        
        return $this->db->rowCount();
    }
    
    /**
     * Delete record
     */
    public function delete($id) {
        $this->db->query(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
        
        return $this->db->rowCount();
    }
    
    /**
     * Count records
     */
    public function count($where = null, $params = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE $where";
        }
        
        $result = $this->db->query($sql, $params)->fetch();
        return $result['count'];
    }
    
    /**
     * Execute raw query
     */
    public function query($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
}