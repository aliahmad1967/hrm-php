<?php
/**
 * Documents Controller
 */

namespace App\Controllers;

use Auth;

class DocumentsController extends Controller {
    
    public function index() {
        $this->checkPermission('documents.view');
        
        $employee_id = $this->getGet('employee_id');
        
        $sql = "SELECT d.*, e.full_name as employee_name, u.full_name as uploaded_by_name 
                FROM documents d 
                JOIN employees e ON d.employee_id = e.id 
                LEFT JOIN users u ON d.uploaded_by = u.id 
                WHERE 1=1";
        $params = [];
        
        if ($employee_id) {
            $sql .= " AND d.employee_id = ?";
            $params[] = $employee_id;
        }
        
        $sql .= " ORDER BY d.created_at DESC";
        
        $documents = $this->db->query($sql, $params)->fetchAll();
        
        $employees = $this->db->query(
            "SELECT id, full_name FROM employees WHERE status = 'active' ORDER BY full_name ASC"
        )->fetchAll();
        
        $this->view('documents/index', [
            'documents' => $documents,
            'employees' => $employees,
            'employee_id' => $employee_id,
            'title' => 'المستندات'
        ]);
    }
    
    public function upload() {
        $this->checkPermission('documents.create');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/documents');
        }
        
        $employee_id = $this->getPost('employee_id');
        $title = sanitize($this->getPost('title'));
        $category = sanitize($this->getPost('category'));
        $description = sanitize($this->getPost('description'));
        
        // Handle file upload
        if (!isset($_FILES['document']) || $_FILES['document']['error'] != 0) {
            $this->setFlash('error', 'الرجاء اختيار ملف');
            $this->redirect(BASE_URL . '/documents');
        }
        
        $config = require CONFIG_PATH . '/app.php';
        $uploadResult = upload_file(
            $_FILES['document'],
            $config['upload_path'] . '/documents',
            $config['allowed_document_types'],
            $config['max_upload_size']
        );
        
        if (!$uploadResult['success']) {
            $this->setFlash('error', $uploadResult['message']);
            $this->redirect(BASE_URL . '/documents');
        }
        
        $this->db->query(
            "INSERT INTO documents (employee_id, title, category, file_path, file_type, file_size, description, uploaded_by) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $employee_id,
                $title,
                $category,
                $uploadResult['filename'],
                mime_content_type($config['upload_path'] . '/documents/' . $uploadResult['filename']),
                $_FILES['document']['size'],
                $description,
                $this->auth->id()
            ]
        );
        
        $this->setFlash('success', 'تم رفع المستند بنجاح');
        $this->redirect(BASE_URL . '/documents');
    }
    
    public function download($id) {
        $this->checkPermission('documents.view');
        
        $document = $this->db->query("SELECT * FROM documents WHERE id = ?", [$id])->fetch();
        
        if (!$document) {
            $this->setFlash('error', 'المستند غير موجود');
            $this->redirect(BASE_URL . '/documents');
        }
        
        $config = require CONFIG_PATH . '/app.php';
        $filePath = $config['upload_path'] . '/documents/' . $document['file_path'];
        
        if (!file_exists($filePath)) {
            $this->setFlash('error', 'الملف غير موجود');
            $this->redirect(BASE_URL . '/documents');
        }
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $document['title'] . '"');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }
    
    public function delete($id) {
        $this->checkPermission('documents.delete');
        
        $document = $this->db->query("SELECT * FROM documents WHERE id = ?", [$id])->fetch();
        
        if (!$document) {
            $this->json(['success' => false, 'message' => 'المستند غير موجود']);
        }
        
        // Delete file
        $config = require CONFIG_PATH . '/app.php';
        $filePath = $config['upload_path'] . '/documents/' . $document['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $this->db->query("DELETE FROM documents WHERE id = ?", [$id]);
        
        $this->json(['success' => true, 'message' => 'تم حذف المستند بنجاح']);
    }
}