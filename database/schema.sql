-- HR Management System Database Schema
-- Arabic RTL HR System
-- Created: 2026-02-15

-- Use database
USE hrm_system;

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if they exist (for clean setup)
DROP TABLE IF EXISTS `documents`;
DROP TABLE IF EXISTS `payroll_items`;
DROP TABLE IF EXISTS `payroll`;
DROP TABLE IF EXISTS `leave_requests`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `backup_logs`;
DROP TABLE IF EXISTS `settings`;

-- Roles table
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `name_ar` VARCHAR(50) NOT NULL,
    `permissions` JSON,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role_id` INT NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` DATETIME,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT,
    INDEX `idx_users_role` (`role_id`),
    INDEX `idx_users_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Departments table
CREATE TABLE `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `name_ar` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `manager_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_departments_manager` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Employees table
CREATE TABLE `employees` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_code` VARCHAR(20) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `national_id` VARCHAR(20) NOT NULL UNIQUE,
    `email` VARCHAR(100),
    `phone` VARCHAR(20),
    `address` TEXT,
    `date_of_birth` DATE,
    `gender` ENUM('male', 'female') DEFAULT 'male',
    `marital_status` ENUM('single', 'married', 'divorced', 'widowed') DEFAULT 'single',
    `photo` VARCHAR(255),
    `department_id` INT,
    `job_title` VARCHAR(100) NOT NULL,
    `employment_type` ENUM('full_time', 'part_time', 'contract', 'intern') DEFAULT 'full_time',
    `hire_date` DATE NOT NULL,
    `contract_end_date` DATE,
    `probation_end_date` DATE,
    `basic_salary` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive', 'on_leave', 'terminated') DEFAULT 'active',
    `bank_name` VARCHAR(100),
    `bank_account` VARCHAR(50),
    `emergency_contact_name` VARCHAR(100),
    `emergency_contact_phone` VARCHAR(20),
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL,
    INDEX `idx_employees_code` (`employee_code`),
    INDEX `idx_employees_department` (`department_id`),
    INDEX `idx_employees_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key for department manager
ALTER TABLE `departments` 
ADD FOREIGN KEY (`manager_id`) REFERENCES `employees`(`id`) ON DELETE SET NULL;

-- Attendance table
CREATE TABLE `attendance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `date` DATE NOT NULL,
    `check_in` TIME,
    `check_out` TIME,
    `status` ENUM('present', 'absent', 'late', 'leave', 'holiday') DEFAULT 'present',
    `work_hours` DECIMAL(4,2) DEFAULT 0,
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_attendance` (`employee_id`, `date`),
    INDEX `idx_attendance_date` (`date`),
    INDEX `idx_attendance_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leave requests table
CREATE TABLE `leave_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `leave_type` ENUM('annual', 'sick', 'unpaid', 'emergency', 'maternity', 'other') NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `days_count` INT NOT NULL,
    `reason` TEXT,
    `status` ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    `approved_by` INT,
    `approved_at` DATETIME,
    `rejection_reason` TEXT,
    `attachments` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_leaves_employee` (`employee_id`),
    INDEX `idx_leaves_status` (`status`),
    INDEX `idx_leaves_dates` (`start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payroll table
CREATE TABLE `payroll` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `pay_period_start` DATE NOT NULL,
    `pay_period_end` DATE NOT NULL,
    `basic_salary` DECIMAL(10,2) NOT NULL,
    `total_allowances` DECIMAL(10,2) DEFAULT 0,
    `total_deductions` DECIMAL(10,2) DEFAULT 0,
    `overtime_hours` DECIMAL(5,2) DEFAULT 0,
    `overtime_amount` DECIMAL(10,2) DEFAULT 0,
    `gross_salary` DECIMAL(10,2) NOT NULL,
    `net_salary` DECIMAL(10,2) NOT NULL,
    `payment_status` ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    `payment_date` DATE,
    `payment_method` VARCHAR(50),
    `notes` TEXT,
    `created_by` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_payroll_employee` (`employee_id`),
    INDEX `idx_payroll_period` (`pay_period_start`, `pay_period_end`),
    INDEX `idx_payroll_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payroll items (allowances and deductions)
CREATE TABLE `payroll_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `payroll_id` INT NOT NULL,
    `type` ENUM('allowance', 'deduction') NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`payroll_id`) REFERENCES `payroll`(`id`) ON DELETE CASCADE,
    INDEX `idx_payroll_items` (`payroll_id`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documents table
CREATE TABLE `documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `category` VARCHAR(50),
    `file_path` VARCHAR(255) NOT NULL,
    `file_type` VARCHAR(50),
    `file_size` INT,
    `description` TEXT,
    `uploaded_by` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_documents_employee` (`employee_id`),
    INDEX `idx_documents_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Backup logs table
CREATE TABLE `backup_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_size` BIGINT,
    `backup_type` ENUM('manual', 'automatic') DEFAULT 'manual',
    `includes_files` TINYINT(1) DEFAULT 1,
    `created_by` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_backups_type` (`backup_type`),
    INDEX `idx_backups_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT,
    `type` VARCHAR(50) DEFAULT 'string',
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default roles
INSERT INTO `roles` (`name`, `name_ar`, `permissions`) VALUES
('admin', 'مدير النظام', '{"dashboard": true, "employees": {"view": true, "create": true, "edit": true, "delete": true}, "departments": {"view": true, "create": true, "edit": true, "delete": true}, "attendance": {"view": true, "create": true, "edit": true, "delete": true}, "leaves": {"view": true, "create": true, "edit": true, "delete": true, "approve": true}, "payroll": {"view": true, "create": true, "edit": true, "delete": true}, "documents": {"view": true, "create": true, "edit": true, "delete": true}, "backup": {"view": true, "create": true, "restore": true, "delete": true}, "users": {"view": true, "create": true, "edit": true, "delete": true}, "reports": {"view": true, "export": true}}'),
('hr', 'موارد بشرية', '{"dashboard": true, "employees": {"view": true, "create": true, "edit": true, "delete": false}, "departments": {"view": true, "create": true, "edit": true, "delete": false}, "attendance": {"view": true, "create": true, "edit": true, "delete": false}, "leaves": {"view": true, "create": true, "edit": true, "delete": false, "approve": true}, "payroll": {"view": true, "create": true, "edit": true, "delete": false}, "documents": {"view": true, "create": true, "edit": true, "delete": false}, "backup": {"view": true, "create": true, "restore": false, "delete": false}, "users": {"view": true, "create": false, "edit": false, "delete": false}, "reports": {"view": true, "export": true}}'),
('manager', 'مدير', '{"dashboard": true, "employees": {"view": true, "create": false, "edit": false, "delete": false}, "departments": {"view": true, "create": false, "edit": false, "delete": false}, "attendance": {"view": true, "create": false, "edit": false, "delete": false}, "leaves": {"view": true, "create": true, "edit": false, "delete": false, "approve": true}, "payroll": {"view": true, "create": false, "edit": false, "delete": false}, "documents": {"view": true, "create": false, "edit": false, "delete": false}, "backup": {"view": false, "create": false, "restore": false, "delete": false}, "users": {"view": false, "create": false, "edit": false, "delete": false}, "reports": {"view": true, "export": false}}');

-- Insert default admin user (password: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `role_id`, `is_active`) VALUES
('admin', 'admin@hr-system.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'مدير النظام', 1, 1);

-- Insert default settings
INSERT INTO `settings` (`key`, `value`, `type`, `description`) VALUES
('company_name', 'نظام إدارة الموارد البشرية', 'string', 'اسم الشركة'),
('company_address', '', 'string', 'عنوان الشركة'),
('company_phone', '', 'string', 'هاتف الشركة'),
('company_email', '', 'string', 'بريد الشركة'),
('work_start_time', '08:00', 'string', 'وقت بداية العمل'),
('work_end_time', '17:00', 'string', 'وقت نهاية العمل'),
('work_days', 'sunday,monday,tuesday,wednesday,thursday', 'string', 'أيام العمل'),
('annual_leave_days', '21', 'integer', 'عدد أيام الإجازة السنوية'),
('probation_period', '90', 'integer', 'فترة التجربة بالأيام'),
('currency', 'IQD', 'string', 'العملة'),
('backup_enabled', '1', 'boolean', 'تفعيل النسخ الاحتياطي'),
('backup_frequency', 'daily', 'string', 'تكرار النسخ الاحتياطي'),
('backup_retention_days', '14', 'integer', 'فترة الاحتفاظ بالنسخ الاحتياطية'),
('backup_encryption', '1', 'boolean', 'تشفير النسخ الاحتياطية'),
('backup_time', '02:00', 'string', 'وقت النسخ الاحتياطي');

-- Insert sample departments
INSERT INTO `departments` (`name`, `name_ar`, `description`) VALUES
('Human Resources', 'الموارد البشرية', 'إدارة الموارد البشرية والشؤون الإدارية'),
('Information Technology', 'تقنية المعلومات', 'قسم تطوير الأنظمة والدعم الفني'),
('Finance', 'المالية', 'إدارة الشؤون المالية والمحاسبة'),
('Marketing', 'التسويق', 'قسم التسويق والعلاقات العامة'),
('Operations', 'العمليات', 'إدارة العمليات والإنتاج');

-- Insert sample employees
INSERT INTO `employees` (`employee_code`, `full_name`, `national_id`, `email`, `phone`, `department_id`, `job_title`, `employment_type`, `hire_date`, `basic_salary`, `status`) VALUES
('EMP001', 'أحمد محمد العلي', '1234567890', 'ahmed@company.local', '0500000001', 1, 'مدير الموارد البشرية', 'full_time', '2020-01-15', 5500000.00, 'active'),
('EMP002', 'فاطمة أحمد الحسن', '1234567891', 'fatima@company.local', '0500000002', 2, 'مطور برمجيات', 'full_time', '2021-03-10', 4200000.00, 'active'),
('EMP003', 'محمد خالد السالم', '1234567892', 'mohammed@company.local', '0500000003', 3, 'محاسب', 'full_time', '2019-06-20', 3500000.00, 'active'),
('EMP004', 'سارة عبدالله الناصر', '1234567893', 'sara@company.local', '0500000004', 4, 'مسؤول تسويق', 'full_time', '2022-01-05', 3200000.00, 'active'),
('EMP005', 'عبدالرحمن سعد القحطاني', '1234567894', 'abdulrahman@company.local', '0500000005', 5, 'مدير عمليات', 'full_time', '2018-09-12', 6500000.00, 'active');

-- Update department managers
UPDATE `departments` SET `manager_id` = 1 WHERE `id` = 1;
UPDATE `departments` SET `manager_id` = 2 WHERE `id` = 2;
UPDATE `departments` SET `manager_id` = 3 WHERE `id` = 3;
UPDATE `departments` SET `manager_id` = 4 WHERE `id` = 4;
UPDATE `departments` SET `manager_id` = 5 WHERE `id` = 5;

-- Insert sample attendance records
INSERT INTO `attendance` (`employee_id`, `date`, `check_in`, `check_out`, `status`, `work_hours`) VALUES
(1, CURDATE(), '08:00:00', '17:00:00', 'present', 8.00),
(2, CURDATE(), '08:15:00', '17:00:00', 'late', 7.75),
(3, CURDATE(), '08:00:00', '17:00:00', 'present', 8.00),
(4, CURDATE(), '08:00:00', '17:00:00', 'present', 8.00),
(5, CURDATE(), '08:00:00', '17:00:00', 'present', 8.00);

-- Insert sample leave request
INSERT INTO `leave_requests` (`employee_id`, `leave_type`, `start_date`, `end_date`, `days_count`, `reason`, `status`) VALUES
(2, 'annual', DATE_ADD(CURDATE(), INTERVAL 7 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 4, 'إجازة سنوية', 'pending');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;