<?php
/**
 * Department Model
 */

namespace App\Models;

class Department extends Model {
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'name_ar', 'description', 'manager_id'];
    
    /**
     * Get department with manager
     */
    public function getWithManager($id) {
        return $this->db->query(
            "SELECT d.*, e.full_name as manager_name, e.photo as manager_photo 
             FROM {$this->table} d 
             LEFT JOIN employees e ON d.manager_id = e.id 
             WHERE d.id = ?",
            [$id]
        )->fetch();
    }
    
    /**
     * Get all departments with employee count
     */
    public function getAllWithStats() {
        return $this->db->query(
            "SELECT d.*, e.full_name as manager_name, 
                    (SELECT COUNT(*) FROM employees WHERE department_id = d.id AND status = 'active') as employee_count 
             FROM {$this->table} d 
             LEFT JOIN employees e ON d.manager_id = e.id 
             ORDER BY d.name_ar ASC"
        )->fetchAll();
    }
    
    /**
     * Get department employees
     */
    public function getEmployees($departmentId) {
        return $this->db->query(
            "SELECT * FROM employees WHERE department_id = ? AND status = 'active' ORDER BY full_name ASC",
            [$departmentId]
        )->fetchAll();
    }
}