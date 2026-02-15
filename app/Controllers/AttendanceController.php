<?php
/**
 * Attendance Controller
 */

namespace App\Controllers;

use App\Models\Employee;
use Auth;

class AttendanceController extends Controller {
    
    protected $employeeModel;
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        $this->employeeModel = new Employee();
    }
    
    public function index() {
        $this->checkPermission('attendance.view');
        
        $date = $this->getGet('date', date('Y-m-d'));
        $department_id = $this->getGet('department_id');
        
        $sql = "SELECT a.*, e.full_name, e.employee_code, d.name_ar as department_name 
                FROM attendance a 
                JOIN employees e ON a.employee_id = e.id 
                LEFT JOIN departments d ON e.department_id = d.id 
                WHERE a.date = ?";
        $params = [$date];
        
        if ($department_id) {
            $sql .= " AND e.department_id = ?";
            $params[] = $department_id;
        }
        
        $sql .= " ORDER BY a.check_in ASC";
        
        $attendance = $this->db->query($sql, $params)->fetchAll();
        
        // Get employees without attendance
        $sql2 = "SELECT e.id, e.full_name, e.employee_code, d.name_ar as department_name 
                 FROM employees e 
                 LEFT JOIN departments d ON e.department_id = d.id 
                 WHERE e.status = 'active' 
                 AND e.id NOT IN (SELECT employee_id FROM attendance WHERE date = ?)";
        $params2 = [$date];
        
        if ($department_id) {
            $sql2 .= " AND e.department_id = ?";
            $params2[] = $department_id;
        }
        
        $missingAttendance = $this->db->query($sql2, $params2)->fetchAll();
        
        $departments = $this->db->query("SELECT * FROM departments ORDER BY name_ar ASC")->fetchAll();
        
        // Calculate stats
        $stats = [
            'present' => 0,
            'absent' => count($missingAttendance),
            'late' => 0,
            'leave' => 0
        ];
        
        foreach ($attendance as $att) {
            $stats[$att['status']]++;
        }
        
        $this->view('attendance/index', [
            'attendance' => $attendance,
            'missingAttendance' => $missingAttendance,
            'departments' => $departments,
            'date' => $date,
            'department_id' => $department_id,
            'stats' => $stats,
            'title' => 'الحضور والانصراف'
        ]);
    }
    
    public function create() {
        $this->checkPermission('attendance.create');
        
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('attendance/create', [
            'employees' => $employees,
            'title' => 'تسجيل حضور'
        ]);
    }
    
    public function store() {
        $this->checkPermission('attendance.create');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/attendance/create');
        }
        
        $employee_id = $this->getPost('employee_id');
        $date = $this->getPost('date');
        $check_in = $this->getPost('check_in');
        $check_out = $this->getPost('check_out');
        $status = $this->getPost('status');
        $notes = sanitize($this->getPost('notes'));
        
        // Calculate work hours
        $work_hours = 0;
        if ($check_in && $check_out) {
            $checkInTime = strtotime($check_in);
            $checkOutTime = strtotime($check_out);
            $work_hours = ($checkOutTime - $checkInTime) / 3600;
        }
        
        // Check if attendance already exists
        $existing = $this->db->query(
            "SELECT id FROM attendance WHERE employee_id = ? AND date = ?",
            [$employee_id, $date]
        )->fetch();
        
        if ($existing) {
            $this->setFlash('error', 'تم تسجيل الحضور لهذا الموظف في هذا التاريخ مسبقاً');
            $this->redirect(BASE_URL . '/attendance/create');
        }
        
        $this->db->query(
            "INSERT INTO attendance (employee_id, date, check_in, check_out, status, work_hours, notes) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$employee_id, $date, $check_in, $check_out, $status, $work_hours, $notes]
        );
        
        $this->setFlash('success', 'تم تسجيل الحضور بنجاح');
        $this->redirect(BASE_URL . '/attendance');
    }
    
    public function edit($id) {
        $this->checkPermission('attendance.edit');
        
        $attendance = $this->db->query(
            "SELECT a.*, e.full_name FROM attendance a JOIN employees e ON a.employee_id = e.id WHERE a.id = ?",
            [$id]
        )->fetch();
        
        if (!$attendance) {
            $this->setFlash('error', 'سجل الحضور غير موجود');
            $this->redirect(BASE_URL . '/attendance');
        }
        
        $this->view('attendance/edit', [
            'attendance' => $attendance,
            'title' => 'تعديل سجل الحضور'
        ]);
    }
    
    public function update($id) {
        $this->checkPermission('attendance.edit');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/attendance/edit/' . $id);
        }
        
        $check_in = $this->getPost('check_in');
        $check_out = $this->getPost('check_out');
        $status = $this->getPost('status');
        $notes = sanitize($this->getPost('notes'));
        
        // Calculate work hours
        $work_hours = 0;
        if ($check_in && $check_out) {
            $checkInTime = strtotime($check_in);
            $checkOutTime = strtotime($check_out);
            $work_hours = ($checkOutTime - $checkInTime) / 3600;
        }
        
        $this->db->query(
            "UPDATE attendance SET check_in = ?, check_out = ?, status = ?, work_hours = ?, notes = ? WHERE id = ?",
            [$check_in, $check_out, $status, $work_hours, $notes, $id]
        );
        
        $this->setFlash('success', 'تم تحديث سجل الحضور بنجاح');
        $this->redirect(BASE_URL . '/attendance');
    }
    
    public function delete($id) {
        $this->checkPermission('attendance.delete');
        
        $this->db->query("DELETE FROM attendance WHERE id = ?", [$id]);
        
        $this->json(['success' => true, 'message' => 'تم حذف السجل بنجاح']);
    }
    
    public function report() {
        $this->checkPermission('attendance.view');
        
        $month = $this->getGet('month', date('m'));
        $year = $this->getGet('year', date('Y'));
        $employee_id = $this->getGet('employee_id');
        
        $sql = "SELECT a.*, e.full_name, e.employee_code, d.name_ar as department_name 
                FROM attendance a 
                JOIN employees e ON a.employee_id = e.id 
                LEFT JOIN departments d ON e.department_id = d.id 
                WHERE MONTH(a.date) = ? AND YEAR(a.date) = ?";
        $params = [$month, $year];
        
        if ($employee_id) {
            $sql .= " AND a.employee_id = ?";
            $params[] = $employee_id;
        }
        
        $sql .= " ORDER BY a.date DESC";
        
        $attendance = $this->db->query($sql, $params)->fetchAll();
        
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('attendance/report', [
            'attendance' => $attendance,
            'employees' => $employees,
            'month' => $month,
            'year' => $year,
            'employee_id' => $employee_id,
            'title' => 'تقرير الحضور والانصراف'
        ]);
    }
}