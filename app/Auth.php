<?php
/**
 * Authentication Class
 * Handles user authentication and session management
 */

class Auth {
    private static $instance = null;
    private $user = null;
    private $db;
    
    private function __construct() {
        $this->db = Database::getInstance();
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is already logged in
        if (isset($_SESSION['user_id'])) {
            $this->user = $this->getUserById($_SESSION['user_id']);
        }
    }
    
    /**
     * Get Auth instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Check if user is logged in
     */
    public function check() {
        return $this->user !== null;
    }
    
    /**
     * Get current user
     */
    public function user() {
        return $this->user;
    }
    
    /**
     * Get user ID
     */
    public function id() {
        return $this->user ? $this->user['id'] : null;
    }
    
    /**
     * Login user
     */
    public function login($username, $password) {
        $user = $this->getUserByUsername($username);
        
        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_active']) {
                return ['success' => false, 'message' => 'الحساب غير نشط. يرجى التواصل مع المسؤول.'];
            }
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['last_activity'] = time();
            
            // Update last login
            $this->db->query(
                "UPDATE users SET last_login = NOW() WHERE id = ?",
                [$user['id']]
            );
            
            $this->user = $user;
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'];
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_unset();
        session_destroy();
        $this->user = null;
    }
    
    /**
     * Check if user has permission
     */
    public function hasPermission($permission) {
        if (!$this->user) {
            return false;
        }
        
        // Admin has all permissions
        if ($this->user['role_name'] === 'admin') {
            return true;
        }
        
        $permissions = json_decode($this->user['permissions'], true);
        
        if (is_array($permission)) {
            // Check multiple permissions (OR logic)
            foreach ($permission as $perm) {
                if ($this->checkSinglePermission($permissions, $perm)) {
                    return true;
                }
            }
            return false;
        }
        
        return $this->checkSinglePermission($permissions, $permission);
    }
    
    /**
     * Check single permission
     */
    private function checkSinglePermission($permissions, $permission) {
        $parts = explode('.', $permission);
        
        if (count($parts) === 1) {
            return isset($permissions[$parts[0]]) && $permissions[$parts[0]] === true;
        }
        
        if (count($parts) === 2) {
            return isset($permissions[$parts[0]][$parts[1]]) && 
                   $permissions[$parts[0]][$parts[1]] === true;
        }
        
        return false;
    }
    
    /**
     * Get user role name
     */
    public function role() {
        return $this->user ? $this->user['role_name'] : null;
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin() {
        return $this->role() === 'admin';
    }
    
    /**
     * Get user by ID
     */
    private function getUserById($id) {
        return $this->db->query(
            "SELECT u.*, r.name as role_name, r.name_ar as role_name_ar, r.permissions 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ?",
            [$id]
        )->fetch();
    }
    
    /**
     * Get user by username
     */
    private function getUserByUsername($username) {
        return $this->db->query(
            "SELECT u.*, r.name as role_name, r.name_ar as role_name_ar, r.permissions 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.username = ? OR u.email = ?",
            [$username, $username]
        )->fetch();
    }
    
    /**
     * Generate CSRF token
     */
    public static function csrf() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCsrf($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}