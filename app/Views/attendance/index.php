<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <?php if ($auth->hasPermission('attendance.create')): ?>
            <a href="<?= base_url('attendance/create') ?>" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>تسجيل حضور
            </a>
            <?php endif; ?>
        </div>
    </header>
    
    <div class="content">
        <?php if ($flash = flash()): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['present'] ?></h3>
                        <p class="mb-0">حاضر</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['late'] ?></h3>
                        <p class="mb-0">متأخر</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['absent'] ?></h3>
                        <p class="mb-0">غائب</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['leave'] ?></h3>
                        <p class="mb-0">إجازة</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= base_url('attendance') ?>" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date" class="form-control" value="<?= $date ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">القسم</label>
                        <select name="department_id" class="form-select">
                            <option value="">جميع الأقسام</option>
                            <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id'] ?>" <?= ($department_id ?? '') == $dept['id'] ? 'selected' : '' ?>><?= $dept['name_ar'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">عرض</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Attendance Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">سجل الحضور ليوم <?= format_date_arabic($date) ?></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>القسم</th>
                                <th>الدخول</th>
                                <th>الخروج</th>
                                <th>الحالة</th>
                                <th>الساعات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance as $att): ?>
                            <tr>
                                <td><?= $att['full_name'] ?></td>
                                <td><?= $att['department_name'] ?: '-' ?></td>
                                <td><?= $att['check_in'] ?: '-' ?></td>
                                <td><?= $att['check_out'] ?: '-' ?></td>
                                <td>
                                    <span class="badge bg-<?= $att['status'] == 'present' ? 'success' : ($att['status'] == 'late' ? 'warning' : ($att['status'] == 'absent' ? 'danger' : 'info')) ?>">
                                        <?= get_status_arabic($att['status']) ?>
                                    </span>
                                </td>
                                <td><?= $att['work_hours'] ?></td>
                                <td>
                                    <?php if ($auth->hasPermission('attendance.edit')): ?>
                                    <a href="<?= base_url('attendance/edit/' . $att['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($attendance)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    لا يوجد سجلات حضور لهذا اليوم
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

<?php $this->endSection(); ?>