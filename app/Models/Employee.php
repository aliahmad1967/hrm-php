<?php
/**
 * Employee Model
 */

namespace App\Models;

class Employee extends Model {
    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_code', 'full_name', 'national_id', 'email', 'phone',
        'address', 'date_of_birth', 'gender', 'marital_status', 'photo',
        'department_id', 'job_title', 'employment_type', 'hire_date',
        'contract_end_date', 'probation_end_date', 'basic_salary',
        'status', 'bank_name', 'bank_account', 'emergency_contact_name',
        'emergency_contact_phone', 'notes'
    ];
    
    /**
     * Get employee with department
     */
    public function getWithDepartment($id) {
        return $this->db->query(
            "SELECT e.*, d.name_ar as department_name, d.manager_id 
             FROM {$this->table} e 
             LEFT JOIN departments d ON e.department_id = d.id 
             WHERE e.id = ?",
            [$id]
        )->fetch();
    }
    
    /**
     * Get all employees with departments
     */
    public function getAllWithDepartments($filters = []) {
        $sql = "SELECT e.*, d.name_ar as department_name 
                FROM {$this->table} e 
                LEFT JOIN departments d ON e.department_id = d.id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['department_id'])) {
            $sql .= " AND e.department_id = ?";
            $params[] = $filters['department_id'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (e.full_name LIKE ? OR e.employee_code LIKE ? OR e.national_id LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY e.created_at DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * Get employee documents
     */
    public function getDocuments($employeeId) {
        return $this->db->query(
            "SELECT * FROM documents WHERE employee_id = ? ORDER BY created_at DESC",
            [$employeeId]
        )->fetchAll();
    }
    
    /**
     * Get employee attendance
     */
    public function getAttendance($employeeId, $month = null, $year = null) {
        $sql = "SELECT * FROM attendance WHERE employee_id = ?";
        $params = [$employeeId];
        
        if ($month && $year) {
            $sql .= " AND MONTH(date) = ? AND YEAR(date) = ?";
            $params[] = $month;
            $params[] = $year;
        }
        
        $sql .= " ORDER BY date DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * Get employee leaves
     */
    public function getLeaves($employeeId, $status = null) {
        $sql = "SELECT lr.*, u.full_name as approved_by_name 
                FROM leave_requests lr 
                LEFT JOIN users u ON lr.approved_by = u.id 
                WHERE lr.employee_id = ?";
        $params = [$employeeId];
        
        if ($status) {
            $sql .= " AND lr.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY lr.created_at DESC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }
    
    /**
     * Get employee payroll history
     */
    public function getPayroll($employeeId) {
        return $this->db->query(
            "SELECT p.*, u.full_name as created_by_name 
             FROM payroll p 
             LEFT JOIN users u ON p.created_by = u.id 
             WHERE p.employee_id = ? 
             ORDER BY p.pay_period_start DESC",
            [$employeeId]
        )->fetchAll();
    }
    
    /**
     * Count employees by status
     */
    public function countByStatus($status) {
        return $this->count("status = ?", [$status]);
    }
    
    /**
     * Check if national ID exists
     */
    public function nationalIdExists($nationalId, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE national_id = ?";
        $params = [$nationalId];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->query($sql, $params)->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->query($sql, $params)->fetch();
        return $result['count'] > 0;
    }
}