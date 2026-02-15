<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'نظام إدارة الموارد البشرية' ?></title>
    
    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    
    <!-- Google Fonts - Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --header-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Login Page Styles */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating > label {
            right: 0;
            left: auto;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            padding: 15px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }
        
        .login-footer {
            text-align: center;
            padding: 0 30px 30px;
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            box-shadow: -5px 0 20px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark-color);
        }
        
        .logo-text span {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            color: var(--secondary-color);
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--secondary-color);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s;
        }
        
        .sidebar-toggle:hover {
            color: var(--primary-color);
        }
        
        .sidebar-nav {
            padding: 20px 15px;
        }
        
        .nav-section {
            margin-bottom: 25px;
        }
        
        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            padding: 0 15px;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }
        
        .nav-link i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .nav-link .badge {
            margin-right: auto;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 20px;
        }
        
        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed + .main-content {
            margin-right: var(--sidebar-collapsed);
        }
        
        /* Header */
        .header {
            background: white;
            height: var(--header-height);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-btn {
            background: none;
            border: none;
            color: var(--secondary-color);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.3s;
            position: relative;
        }
        
        .header-btn:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }
        
        .header-btn .badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.65rem;
            padding: 3px 6px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 10px;
            transition: background 0.3s;
        }
        
        .user-menu:hover {
            background: #f1f5f9;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark-color);
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--secondary-color);
        }
        
        /* Content Area */
        .content {
            padding: 30px;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark-color);
            margin: 0;
        }
        
        .card-body {
            padding: 25px;
        }
        
        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stats-icon.blue {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }
        
        .stats-icon.green {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .stats-icon.orange {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }
        
        .stats-icon.red {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        .stats-info h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }
        
        .stats-info p {
            color: var(--secondary-color);
            margin: 0;
            font-size: 0.9rem;
        }
        
        /* Tables */
        .table-container {
            overflow-x: auto;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px;
            border: none;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #e2e8f0;
        }
        
        /* Buttons */
        .btn {
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 0 15px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .stats-card {
                padding: 20px;
            }
        }
    </style>
    
    <?php if (isset($extra_css)): ?>
        <?= $extra_css ?>
    <?php endif; ?>
</head>
<body>
    <?= $this->yield('content') ?>
    
    <!-- Mobile Navigation -->
    <?php include __DIR__ . '/../partials/mobile-nav.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Sidebar Toggle
        document.querySelector('.sidebar-toggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function(alert) {
                alert.classList.add('fade');
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
    
    <?php if (isset($extra_js)): ?>
        <?= $extra_js ?>
    <?php endif; ?>
</body>
</html>