<?php
/**
 * Installation Script
 * Run this file to set up the database and initial data
 */

require_once __DIR__ . '/app/Database.php';

// Check if already installed
$config = require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "=== نظام إدارة الموارد البشرية - تثبيت النظام ===\n\n";
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['database']} CHARACTER SET {$config['charset']} COLLATE {$config['collation']}");
    echo "✓ تم إنشاء قاعدة البيانات\n";
    
    // Use database
    $pdo->exec("USE {$config['database']}");
    
    // Import schema
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    
    // Split SQL statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Ignore errors for existing tables
                if (strpos($e->getMessage(), 'already exists') === false) {
                    throw $e;
                }
            }
        }
    }
    
    echo "✓ تم إنشاء الجداول\n";
    echo "✓ تم إضافة البيانات الافتراضية\n";
    
    // Create directories
    $directories = [
        __DIR__ . '/public/assets/uploads',
        __DIR__ . '/public/assets/uploads/employees',
        __DIR__ . '/public/assets/uploads/documents',
        __DIR__ . '/public/backups',
        __DIR__ . '/storage/logs',
        __DIR__ . '/storage/cache'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    echo "✓ تم إنشاء المجلدات المطلوبة\n\n";
    
    echo "=== تم التثبيت بنجاح! ===\n\n";
    echo "بيانات الدخول:\n";
    echo "اسم المستخدم: admin\n";
    echo "كلمة المرور: admin123\n\n";
    echo "افتح المتصفح وانتقل إلى: http://localhost/hrm-php\n";
    
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    echo "يرجى التأكد من:\n";
    echo "1. تشغيل خادم MySQL\n";
    echo "2. صحة إعدادات قاعدة البيانات في config/database.php\n";
}