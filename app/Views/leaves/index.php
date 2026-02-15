<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <?php if ($auth->hasPermission('leaves.create')): ?>
            <a href="<?= base_url('leaves/create') ?>" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>طلب إجازة
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
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= base_url('leaves') ?>" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="pending" <?= ($status ?? '') == 'pending' ? 'selected' : '' ?>>معلقة</option>
                            <option value="approved" <?= ($status ?? '') == 'approved' ? 'selected' : '' ?>>موافق</option>
                            <option value="rejected" <?= ($status ?? '') == 'rejected' ? 'selected' : '' ?>>مرفوض</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الموظف</label>
                        <select name="employee_id" class="form-select">
                            <option value="">جميع الموظفين</option>
                            <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>" <?= ($employee_id ?? '') == $emp['id'] ? 'selected' : '' ?>><?= $emp['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">تصفية</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Leaves Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">طلبات الإجازات</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>النوع</th>
                                <th>من</th>
                                <th>إلى</th>
                                <th>الأيام</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leaves as $leave): ?>
                            <tr>
                                <td><?= $leave['full_name'] ?></td>
                                <td><?= get_leave_type_arabic($leave['leave_type']) ?></td>
                                <td><?= format_date($leave['start_date']) ?></td>
                                <td><?= format_date($leave['end_date']) ?></td>
                                <td><?= $leave['days_count'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $leave['status'] == 'approved' ? 'success' : ($leave['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                        <?= get_status_arabic($leave['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($leave['status'] == 'pending' && $auth->hasPermission('leaves.approve')): ?>
                                    <a href="<?= base_url('leaves/approve/' . $leave['id']) ?>" class="btn btn-sm btn-success me-1" onclick="return confirm('تأكيد الموافقة؟')">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="<?= base_url('leaves/reject/' . $leave['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('تأكيد الرفض؟')">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($leaves)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    لا توجد طلبات إجازات
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