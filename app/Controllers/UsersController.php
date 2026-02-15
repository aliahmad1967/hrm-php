<?php
/**
 * Users Controller
 */

namespace App\Controllers;

use Auth;

class UsersController extends Controller {
    
    public function index() {
        $this->checkPermission('users.view');
        
        $users = $this->db->query(
            "SELECT u.*, r.name_ar as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             ORDER BY u.created_at DESC"
        )->fetchAll();
        
        $roles = $this->db->query("SELECT * FROM roles ORDER BY name_ar ASC")->fetchAll();
        
        $this->view('users/index', [
            'users' => $users,
            'roles' => $roles,
            'title' => 'المستخدمين'
        ]);
    }
    
    public function create() {
        $this->checkPermission('users.create');
        
        $roles = $this->db->query("SELECT * FROM roles ORDER BY name_ar ASC")->fetchAll();
        
        $this->view('users/create', [
            'roles' => $roles,
            'title' => 'إضافة مستخدم'
        ]);
    }
    
    public function store() {
        $this->checkPermission('users.create');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/users/create');
        }
        
        $data = [
            'username' => sanitize($this->getPost('username')),
            'email' => sanitize($this->getPost('email')),
            'password' => password_hash($this->getPost('password'), PASSWORD_DEFAULT),
            'full_name' => sanitize($this->getPost('full_name')),
            'role_id' => $this->getPost('role_id'),
            'is_active' => $this->getPost('is_active') ? 1 : 0
        ];
        
        // Check if username exists
        $existing = $this->db->query(
            "SELECT id FROM users WHERE username = ? OR email = ?",
            [$data['username'], $data['email']]
        )->fetch();
        
        if ($existing) {
            $this->setFlash('error', 'اسم المستخدم أو البريد الإلكتروني مستخدم مسبقاً');
            $this->redirect(BASE_URL . '/users/create');
        }
        
        $this->db->query(
            "INSERT INTO users (username, email, password, full_name, role_id, is_active) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [$data['username'], $data['email'], $data['password'], $data['full_name'], $data['role_id'], $data['is_active']]
        );
        
        $this->setFlash('success', 'تم إضافة المستخدم بنجاح');
        $this->redirect(BASE_URL . '/users');
    }
    
    public function edit($id) {
        $this->checkPermission('users.edit');
        
        $user = $this->db->query(
            "SELECT u.*, r.name_ar as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ?",
            [$id]
        )->fetch();
        
        if (!$user) {
            $this->setFlash('error', 'المستخدم غير موجود');
            $this->redirect(BASE_URL . '/users');
        }
        
        $roles = $this->db->query("SELECT * FROM roles ORDER BY name_ar ASC")->fetchAll();
        
        $this->view('users/edit', [
            'user' => $user,
            'roles' => $roles,
            'title' => 'تعديل مستخدم'
        ]);
    }
    
    public function update($id) {
        $this->checkPermission('users.edit');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/users/edit/' . $id);
        }
        
        $data = [
            'username' => sanitize($this->getPost('username')),
            'email' => sanitize($this->getPost('email')),
            'full_name' => sanitize($this->getPost('full_name')),
            'role_id' => $this->getPost('role_id'),
            'is_active' => $this->getPost('is_active') ? 1 : 0
        ];
        
        // Update password if provided
        $password = $this->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            $this->db->query(
                "UPDATE users SET username = ?, email = ?, full_name = ?, role_id = ?, is_active = ?, password = ? WHERE id = ?",
                [$data['username'], $data['email'], $data['full_name'], $data['role_id'], $data['is_active'], $data['password'], $id]
            );
        } else {
            $this->db->query(
                "UPDATE users SET username = ?, email = ?, full_name = ?, role_id = ?, is_active = ? WHERE id = ?",
                [$data['username'], $data['email'], $data['full_name'], $data['role_id'], $data['is_active'], $id]
            );
        }
        
        $this->setFlash('success', 'تم تحديث المستخدم بنجاح');
        $this->redirect(BASE_URL . '/users');
    }
    
    public function delete($id) {
        $this->checkPermission('users.delete');
        
        // Don't allow deleting yourself
        if ($id == $this->auth->id()) {
            $this->json(['success' => false, 'message' => 'لا يمكنك حذف حسابك الخاص']);
        }
        
        $this->db->query("DELETE FROM users WHERE id = ?", [$id]);
        
        $this->json(['success' => true, 'message' => 'تم حذف المستخدم بنجاح']);
    }
    
    public function profile() {
        $user = $this->auth->user();
        
        $this->view('users/profile', [
            'user' => $user,
            'title' => 'الملف الشخصي'
        ]);
    }
    
    public function changePassword() {
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/users/profile');
        }
        
        $current_password = $this->getPost('current_password');
        $new_password = $this->getPost('new_password');
        $confirm_password = $this->getPost('confirm_password');
        
        // Verify current password
        $user = $this->db->query("SELECT password FROM users WHERE id = ?", [$this->auth->id()])->fetch();
        
        if (!password_verify($current_password, $user['password'])) {
            $this->setFlash('error', 'كلمة المرور الحالية غير صحيحة');
            $this->redirect(BASE_URL . '/users/profile');
        }
        
        if ($new_password !== $confirm_password) {
            $this->setFlash('error', 'كلمتا المرور الجديدة غير متطابقتين');
            $this->redirect(BASE_URL . '/users/profile');
        }
        
        if (strlen($new_password) < 8) {
            $this->setFlash('error', 'يجب أن تكون كلمة المرور 8 أحرف على الأقل');
            $this->redirect(BASE_URL . '/users/profile');
        }
        
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $this->db->query("UPDATE users SET password = ? WHERE id = ?", [$hash, $this->auth->id()]);
        
        $this->setFlash('success', 'تم تغيير كلمة المرور بنجاح');
        $this->redirect(BASE_URL . '/users/profile');
    }
}