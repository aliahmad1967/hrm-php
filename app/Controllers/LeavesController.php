<?php
/**
 * Leaves Controller
 */

namespace App\Controllers;

use App\Models\Employee;
use Auth;

class LeavesController extends Controller {
    
    protected $employeeModel;
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        $this->employeeModel = new Employee();
    }
    
    public function index() {
        $this->checkPermission('leaves.view');
        
        $status = $this->getGet('status');
        $employee_id = $this->getGet('employee_id');
        
        $sql = "SELECT lr.*, e.full_name, e.employee_code, u.full_name as approved_by_name 
                FROM leave_requests lr 
                JOIN employees e ON lr.employee_id = e.id 
                LEFT JOIN users u ON lr.approved_by = u.id 
                WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND lr.status = ?";
            $params[] = $status;
        }
        
        if ($employee_id) {
            $sql .= " AND lr.employee_id = ?";
            $params[] = $employee_id;
        }
        
        $sql .= " ORDER BY lr.created_at DESC";
        
        $leaves = $this->db->query($sql, $params)->fetchAll();
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('leaves/index', [
            'leaves' => $leaves,
            'employees' => $employees,
            'status' => $status,
            'employee_id' => $employee_id,
            'title' => 'إدارة الإجازات'
        ]);
    }
    
    public function create() {
        $this->checkPermission('leaves.create');
        
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('leaves/create', [
            'employees' => $employees,
            'title' => 'طلب إجازة جديد'
        ]);
    }
    
    public function store() {
        $this->checkPermission('leaves.create');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/leaves/create');
        }
        
        $employee_id = $this->getPost('employee_id');
        $leave_type = $this->getPost('leave_type');
        $start_date = $this->getPost('start_date');
        $end_date = $this->getPost('end_date');
        $reason = sanitize($this->getPost('reason'));
        
        // Calculate days
        $days_count = days_between($start_date, $end_date);
        
        // Handle attachment upload
        $attachment = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $uploadConfig = require CONFIG_PATH . '/app.php';
            $uploadResult = upload_file(
                $_FILES['attachment'],
                $uploadConfig['upload_path'] . '/documents',
                $uploadConfig['allowed_document_types'],
                $uploadConfig['max_upload_size']
            );
            
            if ($uploadResult['success']) {
                $attachment = $uploadResult['filename'];
            }
        }
        
        $this->db->query(
            "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, days_count, reason, attachments) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$employee_id, $leave_type, $start_date, $end_date, $days_count, $reason, $attachment]
        );
        
        $this->setFlash('success', 'تم تقديم طلب الإجازة بنجاح');
        $this->redirect(BASE_URL . '/leaves');
    }
    
    public function approve($id) {
        $this->checkPermission('leaves.approve');
        
        $this->db->query(
            "UPDATE leave_requests SET status = 'approved', approved_by = ?, approved_at = NOW() WHERE id = ?",
            [$this->auth->id(), $id]
        );
        
        $this->setFlash('success', 'تمت الموافقة على طلب الإجازة');
        $this->redirect(BASE_URL . '/leaves');
    }
    
    public function reject($id) {
        $this->checkPermission('leaves.approve');
        $this->validateMethod('POST');
        
        $reason = sanitize($this->getPost('rejection_reason'));
        
        $this->db->query(
            "UPDATE leave_requests SET status = 'rejected', approved_by = ?, approved_at = NOW(), rejection_reason = ? WHERE id = ?",
            [$this->auth->id(), $reason, $id]
        );
        
        $this->setFlash('success', 'تم رفض طلب الإجازة');
        $this->redirect(BASE_URL . '/leaves');
    }
    
    public function delete($id) {
        $this->checkPermission('leaves.delete');
        
        $this->db->query("DELETE FROM leave_requests WHERE id = ?", [$id]);
        
        $this->json(['success' => true, 'message' => 'تم حذف الطلب بنجاح']);
    }
}