<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <!-- Header -->
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        
        <div class="header-actions">
            <button class="header-btn" title="الإشعارات">
                <i class="fas fa-bell"></i>
                <span class="badge bg-danger">3</span>
            </button>
            
            <button class="header-btn" title="التنبيهات">
                <i class="fas fa-exclamation-triangle"></i>
            </button>
            
            <div class="dropdown">
                <div class="user-menu" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        <?= mb_substr($user['full_name'], 0, 1) ?>
                    </div>
                    <div class="user-info d-none d-md-block">
                        <div class="user-name"><?= $user['full_name'] ?></div>
                        <div class="user-role"><?= $user['role_name_ar'] ?></div>
                    </div>
                    <i class="fas fa-chevron-down ms-2"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= base_url('users/profile') ?>"><i class="fas fa-user me-2"></i>الملف الشخصي</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('settings') ?>"><i class="fas fa-cog me-2"></i>الإعدادات</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج</a></li>
                </ul>
            </div>
        </div>
    </header>
    
    <!-- Content -->
    <div class="content">
        <?php if ($flash = flash()): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?= format_number($stats['total_employees'], 0) ?></h3>
                        <p>إجمالي الموظفين</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon green">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?= format_number($stats['today_attendance'], 0) ?></h3>
                        <p>حضور اليوم</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon orange">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?= format_number($stats['pending_leaves'], 0) ?></h3>
                        <p>إجازات معلقة</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon red">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="stats-info">
                        <h3><?= format_number($stats['active_contracts'], 0) ?></h3>
                        <p>عقود نشطة</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts and Tables Row -->
        <div class="row">
            <!-- Attendance Chart -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            اتجاهات الحضور (آخر 7 أيام)
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Department Distribution -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-chart-pie me-2 text-primary"></i>
                            توزيع الأقسام
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tables Row -->
        <div class="row">
            <!-- Pending Leaves -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <i class="fas fa-clock me-2 text-warning"></i>
                            إجازات معلقة
                        </h5>
                        <a href="<?= base_url('leaves') ?>" class="btn btn-sm btn-primary">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>الموظف</th>
                                        <th>النوع</th>
                                        <th>المدة</th>
                                        <th>الإجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingLeaves as $leave): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                    <?= mb_substr($leave['employee_name'], 0, 1) ?>
                                                </div>
                                                <span><?= $leave['employee_name'] ?></span>
                                            </div>
                                        </td>
                                        <td><?= get_leave_type_arabic($leave['leave_type']) ?></td>
                                        <td><?= $leave['days_count'] ?> يوم</td>
                                        <td>
                                            <a href="<?= base_url('leaves/approve/' . $leave['id']) ?>" class="btn btn-sm btn-success me-1" title="موافقة">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?= base_url('leaves/reject/' . $leave['id']) ?>" class="btn btn-sm btn-danger" title="رفض">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($pendingLeaves)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                                            <p>لا توجد إجازات معلقة</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Contracts -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">
                            <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                            عقود تنتهي قريباً
                        </h5>
                        <a href="<?= base_url('employees') ?>" class="btn btn-sm btn-primary">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>الموظف</th>
                                        <th>القسم</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th>متبقي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingContracts as $emp): ?>
                                    <tr>
                                        <td><?= $emp['full_name'] ?></td>
                                        <td><?= $emp['department_name'] ?></td>
                                        <td><?= format_date($emp['contract_end_date']) ?></td>
                                        <td>
                                            <?php $daysLeft = days_between(date('Y-m-d'), $emp['contract_end_date']); ?>
                                            <span class="badge bg-<?= $daysLeft <= 7 ? 'danger' : 'warning' ?>">
                                                <?= $daysLeft ?> يوم
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($upcomingContracts)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                                            <p>لا توجد عقود تنتهي قريباً</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Employees -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    <i class="fas fa-user-plus me-2 text-success"></i>
                    آخر الموظفين المضافين
                </h5>
                <a href="<?= base_url('employees/create') ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>إضافة موظف
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>الرقم الوظيفي</th>
                                <th>القسم</th>
                                <th>المسمى الوظيفي</th>
                                <th>تاريخ التعيين</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentEmployees as $emp): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                            <?= mb_substr($emp['full_name'], 0, 1) ?>
                                        </div>
                                        <span><?= $emp['full_name'] ?></span>
                                    </div>
                                </td>
                                <td><?= $emp['employee_code'] ?></td>
                                <td><?= $emp['department_name'] ?></td>
                                <td><?= $emp['job_title'] ?></td>
                                <td><?= format_date($emp['hire_date']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $emp['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= get_status_arabic($emp['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Attendance Chart
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
const attendanceData = <?= json_encode($attendanceTrend) ?>;

new Chart(attendanceCtx, {
    type: 'line',
    data: {
        labels: attendanceData.map(d => d.date),
        datasets: [
            {
                label: 'الحضور',
                data: attendanceData.map(d => d.present_count),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'الغياب',
                data: attendanceData.map(d => d.absent_count),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                rtl: true
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Department Chart
const deptCtx = document.getElementById('departmentChart').getContext('2d');
const deptData = <?= json_encode($departmentStats) ?>;

new Chart(deptCtx, {
    type: 'doughnut',
    data: {
        labels: deptData.map(d => d.name_ar),
        datasets: [{
            data: deptData.map(d => d.count),
            backgroundColor: [
                '#2563eb',
                '#10b981',
                '#f59e0b',
                '#ef4444',
                '#8b5cf6',
                '#ec4899'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                rtl: true
            }
        }
    }
});
</script>

<?php $this->endSection(); ?>