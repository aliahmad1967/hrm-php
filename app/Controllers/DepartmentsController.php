<?php
/**
 * Departments Controller
 */

namespace App\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Auth;

class DepartmentsController extends Controller {
    
    protected $departmentModel;
    protected $employeeModel;
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        $this->departmentModel = new Department();
        $this->employeeModel = new Employee();
    }
    
    public function index() {
        $this->checkPermission('departments.view');
        
        $departments = $this->departmentModel->getAllWithStats();
        
        $this->view('departments/index', [
            'departments' => $departments,
            'title' => 'الأقسام'
        ]);
    }
    
    public function create() {
        $this->checkPermission('departments.create');
        
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('departments/create', [
            'employees' => $employees,
            'title' => 'إضافة قسم جديد'
        ]);
    }
    
    public function store() {
        $this->checkPermission('departments.create');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/departments/create');
        }
        
        $data = [
            'name' => sanitize($this->getPost('name')),
            'name_ar' => sanitize($this->getPost('name_ar')),
            'description' => sanitize($this->getPost('description')),
            'manager_id' => $this->getPost('manager_id') ?: null
        ];
        
        if (empty($data['name_ar'])) {
            $this->setFlash('error', 'اسم القسم بالعربية مطلوب');
            $this->redirect(BASE_URL . '/departments/create');
        }
        
        $departmentId = $this->departmentModel->create($data);
        
        if ($departmentId) {
            $this->setFlash('success', 'تم إضافة القسم بنجاح');
            $this->redirect(BASE_URL . '/departments');
        } else {
            $this->setFlash('error', 'حدث خطأ أثناء إضافة القسم');
            $this->redirect(BASE_URL . '/departments/create');
        }
    }
    
    public function edit($id) {
        $this->checkPermission('departments.edit');
        
        $department = $this->departmentModel->getWithManager($id);
        
        if (!$department) {
            $this->setFlash('error', 'القسم غير موجود');
            $this->redirect(BASE_URL . '/departments');
        }
        
        $employees = $this->employeeModel->getAllWithDepartments(['status' => 'active']);
        
        $this->view('departments/edit', [
            'department' => $department,
            'employees' => $employees,
            'title' => 'تعديل القسم'
        ]);
    }
    
    public function update($id) {
        $this->checkPermission('departments.edit');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/departments/edit/' . $id);
        }
        
        $department = $this->departmentModel->find($id);
        
        if (!$department) {
            $this->setFlash('error', 'القسم غير موجود');
            $this->redirect(BASE_URL . '/departments');
        }
        
        $data = [
            'name' => sanitize($this->getPost('name')),
            'name_ar' => sanitize($this->getPost('name_ar')),
            'description' => sanitize($this->getPost('description')),
            'manager_id' => $this->getPost('manager_id') ?: null
        ];
        
        if ($this->departmentModel->update($id, $data)) {
            $this->setFlash('success', 'تم تحديث القسم بنجاح');
            $this->redirect(BASE_URL . '/departments');
        } else {
            $this->setFlash('error', 'حدث خطأ أثناء تحديث القسم');
            $this->redirect(BASE_URL . '/departments/edit/' . $id);
        }
    }
    
    public function delete($id) {
        $this->checkPermission('departments.delete');
        
        $department = $this->departmentModel->find($id);
        
        if (!$department) {
            $this->json(['success' => false, 'message' => 'القسم غير موجود']);
        }
        
        // Check if department has employees
        $employeeCount = $this->employeeModel->count('department_id = ? AND status = "active"', [$id]);
        
        if ($employeeCount > 0) {
            $this->json(['success' => false, 'message' => 'لا يمكن حذف القسم لوجود موظفين مرتبطين به']);
        }
        
        if ($this->departmentModel->delete($id)) {
            $this->json(['success' => true, 'message' => 'تم حذف القسم بنجاح']);
        } else {
            $this->json(['success' => false, 'message' => 'حدث خطأ أثناء الحذف']);
        }
    }
}