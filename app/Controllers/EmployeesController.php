<?php
/**
 * Employees Controller
 */

namespace App\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Auth;

class EmployeesController extends Controller {
    
    protected $employeeModel;
    protected $departmentModel;
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        $this->employeeModel = new Employee();
        $this->departmentModel = new Department();
    }
    
    public function index() {
        $this->checkPermission('employees.view');
        
        $filters = [
            'status' => $this->getGet('status'),
            'department_id' => $this->getGet('department_id'),
            'search' => $this->getGet('search')
        ];
        
        $employees = $this->employeeModel->getAllWithDepartments(array_filter($filters));
        $departments = $this->departmentModel->all('name_ar ASC');
        
        $this->view('employees/index', [
            'employees' => $employees,
            'departments' => $departments,
            'filters' => $filters,
            'title' => 'إدارة الموظفين'
        ]);
    }
    
    public function create() {
        $this->checkPermission('employees.create');
        
        $departments = $this->departmentModel->all('name_ar ASC');
        
        $this->view('employees/create', [
            'departments' => $departments,
            'employee_code' => generate_employee_code(),
            'title' => 'إضافة موظف جديد'
        ]);
    }
    
    public function store() {
        $this->checkPermission('employees.create');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/employees/create');
        }
        
        $data = [
            'employee_code' => $this->getPost('employee_code'),
            'full_name' => sanitize($this->getPost('full_name')),
            'national_id' => sanitize($this->getPost('national_id')),
            'email' => sanitize($this->getPost('email')),
            'phone' => sanitize($this->getPost('phone')),
            'address' => sanitize($this->getPost('address')),
            'date_of_birth' => $this->getPost('date_of_birth'),
            'gender' => $this->getPost('gender'),
            'marital_status' => $this->getPost('marital_status'),
            'department_id' => $this->getPost('department_id') ?: null,
            'job_title' => sanitize($this->getPost('job_title')),
            'employment_type' => $this->getPost('employment_type'),
            'hire_date' => $this->getPost('hire_date'),
            'contract_end_date' => $this->getPost('contract_end_date') ?: null,
            'probation_end_date' => $this->getPost('probation_end_date') ?: null,
            'basic_salary' => $this->getPost('basic_salary'),
            'bank_name' => sanitize($this->getPost('bank_name')),
            'bank_account' => sanitize($this->getPost('bank_account')),
            'emergency_contact_name' => sanitize($this->getPost('emergency_contact_name')),
            'emergency_contact_phone' => sanitize($this->getPost('emergency_contact_phone')),
            'notes' => sanitize($this->getPost('notes'))
        ];
        
        // Validate required fields
        if (empty($data['full_name']) || empty($data['national_id']) || empty($data['hire_date'])) {
            $this->setFlash('error', 'الرجاء ملء جميع الحقول المطلوبة');
            $this->redirect(BASE_URL . '/employees/create');
        }
        
        // Check if national ID already exists
        if ($this->employeeModel->nationalIdExists($data['national_id'])) {
            $this->setFlash('error', 'رقم الهوية مستخدم من قبل');
            $this->redirect(BASE_URL . '/employees/create');
        }
        
        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $uploadConfig = require CONFIG_PATH . '/app.php';
            $uploadResult = upload_file(
                $_FILES['photo'],
                $uploadConfig['upload_path'] . '/employees',
                $uploadConfig['allowed_image_types'],
                $uploadConfig['max_upload_size']
            );
            
            if ($uploadResult['success']) {
                $data['photo'] = $uploadResult['filename'];
            } else {
                $this->setFlash('error', $uploadResult['message']);
                $this->redirect(BASE_URL . '/employees/create');
            }
        }
        
        $employeeId = $this->employeeModel->create($data);
        
        if ($employeeId) {
            $this->setFlash('success', 'تم إضافة الموظف بنجاح');
            $this->redirect(BASE_URL . '/employees');
        } else {
            $this->setFlash('error', 'حدث خطأ أثناء إضافة الموظف');
            $this->redirect(BASE_URL . '/employees/create');
        }
    }
    
    public function edit($id) {
        $this->checkPermission('employees.edit');
        
        $employee = $this->employeeModel->getWithDepartment($id);
        
        if (!$employee) {
            $this->setFlash('error', 'الموظف غير موجود');
            $this->redirect(BASE_URL . '/employees');
        }
        
        $departments = $this->departmentModel->all('name_ar ASC');
        
        $this->view('employees/edit', [
            'employee' => $employee,
            'departments' => $departments,
            'title' => 'تعديل بيانات الموظف'
        ]);
    }
    
    public function update($id) {
        $this->checkPermission('employees.edit');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/employees/edit/' . $id);
        }
        
        $employee = $this->employeeModel->find($id);
        
        if (!$employee) {
            $this->setFlash('error', 'الموظف غير موجود');
            $this->redirect(BASE_URL . '/employees');
        }
        
        $data = [
            'full_name' => sanitize($this->getPost('full_name')),
            'national_id' => sanitize($this->getPost('national_id')),
            'email' => sanitize($this->getPost('email')),
            'phone' => sanitize($this->getPost('phone')),
            'address' => sanitize($this->getPost('address')),
            'date_of_birth' => $this->getPost('date_of_birth'),
            'gender' => $this->getPost('gender'),
            'marital_status' => $this->getPost('marital_status'),
            'department_id' => $this->getPost('department_id') ?: null,
            'job_title' => sanitize($this->getPost('job_title')),
            'employment_type' => $this->getPost('employment_type'),
            'hire_date' => $this->getPost('hire_date'),
            'contract_end_date' => $this->getPost('contract_end_date') ?: null,
            'probation_end_date' => $this->getPost('probation_end_date') ?: null,
            'basic_salary' => $this->getPost('basic_salary'),
            'status' => $this->getPost('status'),
            'bank_name' => sanitize($this->getPost('bank_name')),
            'bank_account' => sanitize($this->getPost('bank_account')),
            'emergency_contact_name' => sanitize($this->getPost('emergency_contact_name')),
            'emergency_contact_phone' => sanitize($this->getPost('emergency_contact_phone')),
            'notes' => sanitize($this->getPost('notes'))
        ];
        
        // Check if national ID already exists
        if ($this->employeeModel->nationalIdExists($data['national_id'], $id)) {
            $this->setFlash('error', 'رقم الهوية مستخدم من قبل');
            $this->redirect(BASE_URL . '/employees/edit/' . $id);
        }
        
        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $uploadConfig = require CONFIG_PATH . '/app.php';
            $uploadResult = upload_file(
                $_FILES['photo'],
                $uploadConfig['upload_path'] . '/employees',
                $uploadConfig['allowed_image_types'],
                $uploadConfig['max_upload_size']
            );
            
            if ($uploadResult['success']) {
                // Delete old photo if exists
                if ($employee['photo'] && file_exists($uploadConfig['upload_path'] . '/employees/' . $employee['photo'])) {
                    unlink($uploadConfig['upload_path'] . '/employees/' . $employee['photo']);
                }
                $data['photo'] = $uploadResult['filename'];
            } else {
                $this->setFlash('error', $uploadResult['message']);
                $this->redirect(BASE_URL . '/employees/edit/' . $id);
            }
        }
        
        if ($this->employeeModel->update($id, $data)) {
            $this->setFlash('success', 'تم تحديث بيانات الموظف بنجاح');
            $this->redirect(BASE_URL . '/employees');
        } else {
            $this->setFlash('error', 'حدث خطأ أثناء تحديث البيانات');
            $this->redirect(BASE_URL . '/employees/edit/' . $id);
        }
    }
    
    public function delete($id) {
        $this->checkPermission('employees.delete');
        
        $employee = $this->employeeModel->find($id);
        
        if (!$employee) {
            $this->json(['success' => false, 'message' => 'الموظف غير موجود']);
        }
        
        // Delete photo if exists
        if ($employee['photo']) {
            $uploadConfig = require CONFIG_PATH . '/app.php';
            $photoPath = $uploadConfig['upload_path'] . '/employees/' . $employee['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
        
        if ($this->employeeModel->delete($id)) {
            $this->json(['success' => true, 'message' => 'تم حذف الموظف بنجاح']);
        } else {
            $this->json(['success' => false, 'message' => 'حدث خطأ أثناء الحذف']);
        }
    }
    
    public function show($id) {
        $this->checkPermission('employees.view');
        
        $employee = $this->employeeModel->getWithDepartment($id);
        
        if (!$employee) {
            $this->setFlash('error', 'الموظف غير موجود');
            $this->redirect(BASE_URL . '/employees');
        }
        
        $documents = $this->employeeModel->getDocuments($id);
        $attendance = $this->employeeModel->getAttendance($id, date('m'), date('Y'));
        $leaves = $this->employeeModel->getLeaves($id);
        $payroll = $this->employeeModel->getPayroll($id);
        
        $this->view('employees/show', [
            'employee' => $employee,
            'documents' => $documents,
            'attendance' => $attendance,
            'leaves' => $leaves,
            'payroll' => $payroll,
            'title' => 'ملف الموظف: ' . $employee['full_name']
        ]);
    }
}