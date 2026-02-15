<?php
/**
 * Routes Configuration
 */

// Auth Routes
$router->add('login', ['controller' => 'Auth', 'action' => 'login']);
$router->add('auth/login', ['controller' => 'Auth', 'action' => 'login']);
$router->add('auth/logout', ['controller' => 'Auth', 'action' => 'logout']);
$router->add('auth/forgot-password', ['controller' => 'Auth', 'action' => 'forgotPassword']);

// Root redirect to login
$router->add('', ['controller' => 'Auth', 'action' => 'login']);

// Dashboard
$router->add('dashboard', ['controller' => 'Dashboard', 'action' => 'index']);

// Employees Routes
$router->add('employees', ['controller' => 'Employees', 'action' => 'index']);
$router->add('employees/create', ['controller' => 'Employees', 'action' => 'create']);
$router->add('employees/store', ['controller' => 'Employees', 'action' => 'store']);
$router->add('employees/edit/{id}', ['controller' => 'Employees', 'action' => 'edit']);
$router->add('employees/update/{id}', ['controller' => 'Employees', 'action' => 'update']);
$router->add('employees/delete/{id}', ['controller' => 'Employees', 'action' => 'delete']);
$router->add('employees/view/{id}', ['controller' => 'Employees', 'action' => 'show']);
$router->add('employees/export/pdf', ['controller' => 'Employees', 'action' => 'exportPdf']);
$router->add('employees/export/excel', ['controller' => 'Employees', 'action' => 'exportExcel']);

// Departments Routes
$router->add('departments', ['controller' => 'Departments', 'action' => 'index']);
$router->add('departments/create', ['controller' => 'Departments', 'action' => 'create']);
$router->add('departments/store', ['controller' => 'Departments', 'action' => 'store']);
$router->add('departments/edit/{id}', ['controller' => 'Departments', 'action' => 'edit']);
$router->add('departments/update/{id}', ['controller' => 'Departments', 'action' => 'update']);
$router->add('departments/delete/{id}', ['controller' => 'Departments', 'action' => 'delete']);

// Attendance Routes
$router->add('attendance', ['controller' => 'Attendance', 'action' => 'index']);
$router->add('attendance/create', ['controller' => 'Attendance', 'action' => 'create']);
$router->add('attendance/store', ['controller' => 'Attendance', 'action' => 'store']);
$router->add('attendance/edit/{id}', ['controller' => 'Attendance', 'action' => 'edit']);
$router->add('attendance/update/{id}', ['controller' => 'Attendance', 'action' => 'update']);
$router->add('attendance/delete/{id}', ['controller' => 'Attendance', 'action' => 'delete']);
$router->add('attendance/report', ['controller' => 'Attendance', 'action' => 'report']);
$router->add('attendance/export/pdf', ['controller' => 'Attendance', 'action' => 'exportPdf']);
$router->add('attendance/export/excel', ['controller' => 'Attendance', 'action' => 'exportExcel']);

// Leaves Routes
$router->add('leaves', ['controller' => 'Leaves', 'action' => 'index']);
$router->add('leaves/create', ['controller' => 'Leaves', 'action' => 'create']);
$router->add('leaves/store', ['controller' => 'Leaves', 'action' => 'store']);
$router->add('leaves/edit/{id}', ['controller' => 'Leaves', 'action' => 'edit']);
$router->add('leaves/update/{id}', ['controller' => 'Leaves', 'action' => 'update']);
$router->add('leaves/delete/{id}', ['controller' => 'Leaves', 'action' => 'delete']);
$router->add('leaves/approve/{id}', ['controller' => 'Leaves', 'action' => 'approve']);
$router->add('leaves/reject/{id}', ['controller' => 'Leaves', 'action' => 'reject']);
$router->add('leaves/export/pdf', ['controller' => 'Leaves', 'action' => 'exportPdf']);
$router->add('leaves/export/excel', ['controller' => 'Leaves', 'action' => 'exportExcel']);

// Payroll Routes
$router->add('payroll', ['controller' => 'Payroll', 'action' => 'index']);
$router->add('payroll/create', ['controller' => 'Payroll', 'action' => 'create']);
$router->add('payroll/store', ['controller' => 'Payroll', 'action' => 'store']);
$router->add('payroll/edit/{id}', ['controller' => 'Payroll', 'action' => 'edit']);
$router->add('payroll/update/{id}', ['controller' => 'Payroll', 'action' => 'update']);
$router->add('payroll/delete/{id}', ['controller' => 'Payroll', 'action' => 'delete']);
$router->add('payroll/view/{id}', ['controller' => 'Payroll', 'action' => 'view']);
$router->add('payroll/payslip/{id}', ['controller' => 'Payroll', 'action' => 'payslip']);
$router->add('payroll/export/pdf', ['controller' => 'Payroll', 'action' => 'exportPdf']);
$router->add('payroll/export/excel', ['controller' => 'Payroll', 'action' => 'exportExcel']);

// Documents Routes
$router->add('documents', ['controller' => 'Documents', 'action' => 'index']);
$router->add('documents/upload', ['controller' => 'Documents', 'action' => 'upload']);
$router->add('documents/download/{id}', ['controller' => 'Documents', 'action' => 'download']);
$router->add('documents/delete/{id}', ['controller' => 'Documents', 'action' => 'delete']);

// Backup Routes
$router->add('backup', ['controller' => 'Backup', 'action' => 'index']);
$router->add('backup/create', ['controller' => 'Backup', 'action' => 'create']);
$router->add('backup/download/{filename}', ['controller' => 'Backup', 'action' => 'download']);
$router->add('backup/delete/{filename}', ['controller' => 'Backup', 'action' => 'delete']);
$router->add('backup/restore', ['controller' => 'Backup', 'action' => 'restore']);
$router->add('backup/settings', ['controller' => 'Backup', 'action' => 'settings']);

// Users Routes
$router->add('users', ['controller' => 'Users', 'action' => 'index']);
$router->add('users/create', ['controller' => 'Users', 'action' => 'create']);
$router->add('users/store', ['controller' => 'Users', 'action' => 'store']);
$router->add('users/edit/{id}', ['controller' => 'Users', 'action' => 'edit']);
$router->add('users/update/{id}', ['controller' => 'Users', 'action' => 'update']);
$router->add('users/delete/{id}', ['controller' => 'Users', 'action' => 'delete']);
$router->add('users/profile', ['controller' => 'Users', 'action' => 'profile']);
$router->add('users/change-password', ['controller' => 'Users', 'action' => 'changePassword']);

// Settings Routes
$router->add('settings', ['controller' => 'Settings', 'action' => 'index']);
$router->add('settings/update', ['controller' => 'Settings', 'action' => 'update']);