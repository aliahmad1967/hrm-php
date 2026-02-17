<?php
/**
 * Helper Functions
 */

/**
 * Get base URL
 */
function base_url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 */
function asset($path) {
    return base_url('public/assets/' . ltrim($path, '/'));
}

/**
 * Get upload URL
 */
function upload_url($path) {
    return base_url('public/assets/uploads/' . ltrim($path, '/'));
}

/**
 * Format date to Arabic
 */
function format_date($date, $format = 'Y-m-d') {
    if (!$date) return '-';
    $dateObj = new DateTime($date);
    return $dateObj->format($format);
}

/**
 * Format date to Arabic long format
 */
function format_date_arabic($date) {
    if (!$date) return '-';
    
    $months = [
        'January' => 'يناير', 'February' => 'فبراير', 'March' => 'مارس',
        'April' => 'أبريل', 'May' => 'مايو', 'June' => 'يونيو',
        'July' => 'يوليو', 'August' => 'أغسطس', 'September' => 'سبتمبر',
        'October' => 'أكتوبر', 'November' => 'نوفمبر', 'December' => 'ديسمبر'
    ];
    
    $days = [
        'Saturday' => 'السبت', 'Sunday' => 'الأحد', 'Monday' => 'الإثنين',
        'Tuesday' => 'الثلاثاء', 'Wednesday' => 'الأربعاء',
        'Thursday' => 'الخميس', 'Friday' => 'الجمعة'
    ];
    
    $dateObj = new DateTime($date);
    $dayName = $days[$dateObj->format('l')];
    $monthName = $months[$dateObj->format('F')];
    
    return $dayName . ' ' . $dateObj->format('d') . ' ' . $monthName . ' ' . $dateObj->format('Y');
}

/**
 * Format number to Arabic
 */
function format_number($number, $decimals = 2) {
    return number_format($number, $decimals);
}

/**
 * Format currency
 */
function format_currency($amount, $currency = 'د.ع') {
    return format_number($amount) . ' ' . $currency;
}

/**
 * Get Arabic status
 */
function get_status_arabic($status) {
    $statuses = [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'on_leave' => 'في إجازة',
        'terminated' => 'متوقف',
        'present' => 'حاضر',
        'absent' => 'غائب',
        'late' => 'متأخر',
        'leave' => 'إجازة',
        'holiday' => 'عطلة',
        'pending' => 'معلق',
        'approved' => 'موافق',
        'rejected' => 'مرفوض',
        'cancelled' => 'ملغي',
        'paid' => 'مدفوع'
    ];
    
    return isset($statuses[$status]) ? $statuses[$status] : $status;
}

/**
 * Get Arabic leave type
 */
function get_leave_type_arabic($type) {
    $types = [
        'annual' => 'سنوية',
        'sick' => 'مرضية',
        'unpaid' => 'بدون راتب',
        'emergency' => 'طارئة',
        'maternity' => 'أمومة',
        'other' => 'أخرى'
    ];
    
    return isset($types[$type]) ? $types[$type] : $type;
}

/**
 * Get Arabic employment type
 */
function get_employment_type_arabic($type) {
    $types = [
        'full_time' => 'دوام كامل',
        'part_time' => 'دوام جزئي',
        'contract' => 'عقد',
        'intern' => 'متدرب'
    ];
    
    return isset($types[$type]) ? $types[$type] : $type;
}

/**
 * Calculate age
 */
function calculate_age($birthdate) {
    if (!$birthdate) return 0;
    $birth = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birth);
    return $age->y;
}

/**
 * Calculate days between dates
 */
function days_between($start, $end) {
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);
    $interval = $startDate->diff($endDate);
    return $interval->days + 1;
}

/**
 * Generate random string
 */
function generate_random_string($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Generate employee code
 */
function generate_employee_code($prefix = 'EMP') {
    $db = Database::getInstance();
    $result = $db->query("SELECT MAX(CAST(SUBSTRING(employee_code, 4) AS UNSIGNED)) as max_code FROM employees WHERE employee_code LIKE '$prefix%'")->fetch();
    $nextCode = ($result['max_code'] ?? 0) + 1;
    return $prefix . str_pad($nextCode, 3, '0', STR_PAD_LEFT);
}

/**
 * Upload file
 */
function upload_file($file, $directory, $allowedTypes = [], $maxSize = 5242880) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'message' => 'لم يتم اختيار ملف'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'حجم الملف كبير جداً'];
    }
    
    $fileType = mime_content_type($file['tmp_name']);
    
    if (!empty($allowedTypes) && !in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'نوع الملف غير مسموح به'];
    }
    
    $filename = generate_random_string(16) . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $uploadPath = $directory . '/' . $filename;
    
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $uploadPath];
    }
    
    return ['success' => false, 'message' => 'فشل في رفع الملف'];
}

/**
 * Sanitize input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Saudi phone number
 */
function is_valid_saudi_phone($phone) {
    return preg_match('/^(05|5)[0-9]{8}$/', $phone);
}

/**
 * Validate Saudi national ID
 */
function is_valid_saudi_id($id) {
    return preg_match('/^[12][0-9]{9}$/', $id);
}

/**
 * Create flash message
 */
function flash($type = null, $message = null) {
    if ($type && $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        return true;
    }
    
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    
    return null;
}

/**
 * Get current URL
 */
function current_url() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . 
           "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

/**
 * Redirect back
 */
function redirect_back() {
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
    header("Location: $referer");
    exit;
}

/**
 * Check if string contains Arabic
 */
function contains_arabic($string) {
    return preg_match('/[\x{0600}-\x{06FF}]/u', $string);
}

/**
 * Convert number to Arabic numerals
 */
function to_arabic_numerals($number) {
    $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    return str_replace($western, $arabic, $number);
}

/**
 * Generate CSRF token field
 */
function csrf_field() {
    $token = Auth::csrf();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Get CSRF token
 */
function csrf_token() {
    return Auth::csrf();
}

/**
 * Encrypt data
 */
function encrypt_data($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

/**
 * Decrypt data
 */
function decrypt_data($data, $key) {
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}