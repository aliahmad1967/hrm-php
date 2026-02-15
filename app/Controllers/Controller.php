<?php
/**
 * Base Controller Class
 */

namespace App\Controllers;

use App\Models\User;

class Controller {
    protected $route_params = [];
    protected $view;
    protected $db;
    protected $auth;
    
    public function __construct($route_params) {
        $this->route_params = $route_params;
        $this->view = new \View();
        $this->db = \Database::getInstance();
        $this->auth = \Auth::getInstance();
        
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set default timezone
        date_default_timezone_set('Asia/Riyadh');
        
        // Check authentication for protected routes
        $this->checkAuth();
    }
    
    /**
     * Check if user is authenticated
     */
    protected function checkAuth() {
        $publicRoutes = ['auth/login', 'auth/logout', 'auth/forgot-password'];
        $currentRoute = isset($_GET['url']) ? $_GET['url'] : '';
        
        if (!in_array($currentRoute, $publicRoutes)) {
            if (!$this->auth->check()) {
                if ($this->isAjax()) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Unauthorized']);
                    exit;
                } else {
                    header('Location: ' . BASE_URL . '/auth/login');
                    exit;
                }
            }
        }
    }
    
    /**
     * Magic method to handle action filters
     */
    public function __call($name, $args) {
        $method = $name . 'Action';
        
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }
    
    /**
     * Before action filter
     */
    protected function before() {
        // Can be overridden in child controllers
    }
    
    /**
     * After action filter
     */
    protected function after() {
        // Can be overridden in child controllers
    }
    
    /**
     * Render view
     */
    protected function view($view, $data = []) {
        $data['auth'] = $this->auth;
        $data['user'] = $this->auth->user();
        $content = $this->view->render($view, $data);
        echo $content;
        return $content;
    }
    
    /**
     * Render JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url, $code = 302) {
        header("Location: $url", true, $code);
        exit;
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get flash message
     */
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Validate request method
     */
    protected function validateMethod($method) {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            $this->json(['error' => 'Method not allowed'], 405);
        }
    }
    
    /**
     * Get POST data
     */
    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    
    /**
     * Get GET data
     */
    protected function getGet($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    /**
     * Check permission
     */
    protected function checkPermission($permission) {
        if (!$this->auth->hasPermission($permission)) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Permission denied'], 403);
            } else {
                $this->setFlash('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
                $this->redirect(BASE_URL . '/dashboard');
            }
        }
    }
}