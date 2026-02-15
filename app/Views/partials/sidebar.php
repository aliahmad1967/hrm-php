<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="logo-text">
                HRM System
                <span>نظام الموارد البشرية</span>
            </div>
        </div>
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <!-- Main Menu -->
        <div class="nav-section">
            <div class="nav-section-title">القائمة الرئيسية</div>
            
            <div class="nav-item">
                <a href="<?= base_url('dashboard') ?>" class="nav-link <?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>لوحة التحكم</span>
                </a>
            </div>
            
            <?php if ($auth->hasPermission('employees.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('employees') ?>" class="nav-link <?= strpos(current_url(), 'employees') !== false ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>الموظفين</span>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ($auth->hasPermission('departments.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('departments') ?>" class="nav-link <?= strpos(current_url(), 'departments') !== false ? 'active' : '' ?>">
                    <i class="fas fa-building"></i>
                    <span>الأقسام</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- HR Operations -->
        <div class="nav-section">
            <div class="nav-section-title">عمليات الموارد البشرية</div>
            
            <?php if ($auth->hasPermission('attendance.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('attendance') ?>" class="nav-link <?= strpos(current_url(), 'attendance') !== false ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i>
                    <span>الحضور والانصراف</span>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ($auth->hasPermission('leaves.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('leaves') ?>" class="nav-link <?= strpos(current_url(), 'leaves') !== false ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>الإجازات</span>
                    <?php if (isset($pendingLeavesCount) && $pendingLeavesCount > 0): ?>
                        <span class="badge bg-danger"><?= $pendingLeavesCount ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ($auth->hasPermission('payroll.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('payroll') ?>" class="nav-link <?= strpos(current_url(), 'payroll') !== false ? 'active' : '' ?>">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>الرواتب</span>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ($auth->hasPermission('documents.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('documents') ?>" class="nav-link <?= strpos(current_url(), 'documents') !== false ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>المستندات</span>
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Administration -->
        <div class="nav-section">
            <div class="nav-section-title">الإدارة</div>
            
            <?php if ($auth->hasPermission('users.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('users') ?>" class="nav-link <?= strpos(current_url(), 'users') !== false ? 'active' : '' ?>">
                    <i class="fas fa-user-shield"></i>
                    <span>المستخدمين</span>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ($auth->hasPermission('backup.view')): ?>
            <div class="nav-item">
                <a href="<?= base_url('backup') ?>" class="nav-link <?= strpos(current_url(), 'backup') !== false ? 'active' : '' ?>">
                    <i class="fas fa-database"></i>
                    <span>النسخ الاحتياطي</span>
                </a>
            </div>
            <?php endif; ?>
            
            <div class="nav-item">
                <a href="<?= base_url('settings') ?>" class="nav-link <?= strpos(current_url(), 'settings') !== false ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span>الإعدادات</span>
                </a>
            </div>
        </div>
    </nav>
</aside>