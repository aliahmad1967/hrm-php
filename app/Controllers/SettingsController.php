<?php
/**
 * Settings Controller
 */

namespace App\Controllers;

class SettingsController extends Controller {
    
    public function index() {
        $this->checkPermission('settings.view');
        
        $settings = $this->db->query("SELECT * FROM settings ORDER BY id ASC")->fetchAll();
        
        $this->view('settings/index', [
            'settings' => $settings,
            'title' => 'الإعدادات'
        ]);
    }
    
    public function update() {
        $this->checkPermission('settings.edit');
        $this->validateMethod('POST');
        
        if (!Auth::validateCsrf($this->getPost('csrf_token'))) {
            $this->setFlash('error', 'Invalid CSRF token');
            $this->redirect(BASE_URL . '/settings');
        }
        
        $settings = $this->getPost('settings', []);
        
        foreach ($settings as $key => $value) {
            $this->db->query(
                "UPDATE settings SET value = ? WHERE `key` = ?",
                [$value, $key]
            );
        }
        
        $this->setFlash('success', 'تم حفظ الإعدادات بنجاح');
        $this->redirect(BASE_URL . '/settings');
    }
}