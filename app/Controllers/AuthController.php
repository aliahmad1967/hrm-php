<?php
/**
 * Auth Controller
 */

namespace App\Controllers;

class AuthController extends Controller {
    
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->auth->check()) {
            $this->redirect(BASE_URL . '/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            $remember = $this->getPost('remember');
            
            if (empty($username) || empty($password)) {
                $this->setFlash('error', 'الرجاء إدخال اسم المستخدم وكلمة المرور');
                $this->redirect(BASE_URL . '/auth/login');
            }
            
            try {
                $result = $this->auth->login($username, $password);
                
                if ($result['success']) {
                    $this->setFlash('success', 'تم تسجيل الدخول بنجاح');
                    $this->redirect(BASE_URL . '/dashboard');
                } else {
                    $this->setFlash('error', $result['message']);
                    $this->redirect(BASE_URL . '/auth/login');
                }
            } catch (Exception $e) {
                $this->setFlash('error', 'خطأ في النظام: ' . $e->getMessage());
                $this->redirect(BASE_URL . '/auth/login');
            }
        }
        
        $this->view('auth/login');
    }
    
    public function logout() {
        $this->auth->logout();
        $this->setFlash('success', 'تم تسجيل الخروج بنجاح');
        $this->redirect(BASE_URL . '/auth/login');
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->getPost('email');
            
            if (empty($email)) {
                $this->setFlash('error', 'الرجاء إدخال البريد الإلكتروني');
                $this->redirect(BASE_URL . '/auth/forgot-password');
            }
            
            // TODO: Implement password reset logic
            $this->setFlash('info', 'تم إرسال تعليمات إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
            $this->redirect(BASE_URL . '/auth/login');
        }
        
        $this->view('auth/forgot-password');
    }
}