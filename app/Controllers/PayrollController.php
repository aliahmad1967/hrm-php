<?php
/**
 * Payroll Controller
 */

namespace App\Controllers;

use App\Models\Employee;
use Auth;

class PayrollController extends Controller {
    
    protected $employeeModel;
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        $this->employeeModel = new Employee();
    }
    
    public function index() {
        $this->checkPermission('payroll.view');
        
        $month = $this->getGet('month', date('m'));
        $year = $this->getGet('year', date('Y'));
        $employee_id = $this->getGet('employee_id');
        
        $sql = "SELECT p.*, e.full_name, e.employee_code, e.bank_name, e.bank_account, u.full_name as created_by_name 
                FROM payroll p 
                JOIN employees e ON p.employee_id = e.id 
                LEFT JOIN users u ON p.created_by = u.id 
                WHERE MONTH(p.pay_period_start) = ? AND YEAR(p.pay_period_start) = ?";
        $params = [$month, $year];
        
        if ($employee_id) {
            $sql .= " AND p.employee_id = ?";
            $params[] = $employee_id;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        $payroll = $this->db->query($sql, $params)->fetchAll();
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('payroll/index', [
            'payroll' => $payroll,
            'employees' => $employees,
            'month' => $month,
            'year' => $year,
            'employee_id' => $employee_id,
            'title' => 'إدارة الرواتب'
        ]);
    }
    
    public function create() {
        $this->checkPermission('payroll.create');
        
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('payroll/create', [
            'employees' => $employees,
            'title' => 'إنشاء كشف راتب'
        ]);
    }
    
    public function store() {
        $this->checkPermission('payroll.create');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/payroll/create');
        }
        
        $employee_id = $this->getPost('employee_id');
        $pay_period_start = $this->getPost('pay_period_start');
        $pay_period_end = $this->getPost('pay_period_end');
        $basic_salary = floatval($this->getPost('basic_salary'));
        
        // Calculate allowances and deductions
        $allowances = $this->getPost('allowances', []);
        $deductions = $this->getPost('deductions', []);
        $overtime_hours = floatval($this->getPost('overtime_hours', 0));
        $overtime_rate = floatval($this->getPost('overtime_rate', 0));
        
        $total_allowances = 0;
        foreach ($allowances as $allowance) {
            $total_allowances += floatval($allowance['amount']);
        }
        
        $total_deductions = 0;
        foreach ($deductions as $deduction) {
            $total_deductions += floatval($deduction['amount']);
        }
        
        $overtime_amount = $overtime_hours * $overtime_rate;
        $gross_salary = $basic_salary + $total_allowances + $overtime_amount;
        $net_salary = $gross_salary - $total_deductions;
        
        // Create payroll record
        $this->db->query(
            "INSERT INTO payroll (employee_id, pay_period_start, pay_period_end, basic_salary, 
             total_allowances, total_deductions, overtime_hours, overtime_amount, gross_salary, 
             net_salary, created_by) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$employee_id, $pay_period_start, $pay_period_end, $basic_salary, $total_allowances, 
             $total_deductions, $overtime_hours, $overtime_amount, $gross_salary, $net_salary, 
             $this->auth->id()]
        );
        
        $payroll_id = $this->db->lastInsertId();
        
        // Add payroll items
        foreach ($allowances as $allowance) {
            if (!empty($allowance['name'])) {
                $this->db->query(
                    "INSERT INTO payroll_items (payroll_id, type, name, amount, description) 
                     VALUES (?, 'allowance', ?, ?, ?)",
                    [$payroll_id, $allowance['name'], $allowance['amount'], $allowance['description']]
                );
            }
        }
        
        foreach ($deductions as $deduction) {
            if (!empty($deduction['name'])) {
                $this->db->query(
                    "INSERT INTO payroll_items (payroll_id, type, name, amount, description) 
                     VALUES (?, 'deduction', ?, ?, ?)",
                    [$payroll_id, $deduction['name'], $deduction['amount'], $deduction['description']]
                );
            }
        }
        
        $this->setFlash('success', 'تم إنشاء كشف الراتب بنجاح');
        $this->redirect(BASE_URL . '/payroll');
    }
    
    public function payslip($id) {
        $this->checkPermission('payroll.view');
        
        $payroll = $this->db->query(
            "SELECT p.*, e.full_name, e.employee_code, e.job_title, d.name_ar as department_name 
             FROM payroll p 
             JOIN employees e ON p.employee_id = e.id 
             LEFT JOIN departments d ON e.department_id = d.id 
             WHERE p.id = ?",
            [$id]
        )->fetch();
        
        if (!$payroll) {
            $this->setFlash('error', 'كشف الراتب غير موجود');
            $this->redirect(BASE_URL . '/payroll');
        }
        
        $items = $this->db->query(
            "SELECT * FROM payroll_items WHERE payroll_id = ?",
            [$id]
        )->fetchAll();
        
        $this->view('payroll/payslip', [
            'payroll' => $payroll,
            'items' => $items,
            'title' => 'كشف الراتب - ' . $payroll['full_name']
        ]);
    }
    
    public function delete($id) {
        $this->checkPermission('payroll.delete');
        
        $this->db->query("DELETE FROM payroll_items WHERE payroll_id = ?", [$id]);
        $this->db->query("DELETE FROM payroll WHERE id = ?", [$id]);
        
        $this->json(['success' => true, 'message' => 'تم حذف كشف الراتب بنجاح']);
    }
}