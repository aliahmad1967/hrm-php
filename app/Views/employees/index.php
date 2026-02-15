<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <!-- Header -->
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        
        <div class="header-actions">
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
        
        <!-- Actions Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <form action="<?= base_url('employees') ?>" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="بحث..." value="<?= $filters['search'] ?? '' ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6 text-md-start">
                        <?php if ($auth->hasPermission('employees.create')): ?>
                        <a href="<?= base_url('employees/create') ?>" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>إضافة موظف
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= base_url('employees') ?>" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">القسم</label>
                        <select name="department_id" class="form-select">
                            <option value="">جميع الأقسام</option>
                            <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id'] ?>" <?= ($filters['department_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                                <?= $dept['name_ar'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">جميع الحالات</option>
                            <option value="active" <?= ($filters['status'] ?? '') == 'active' ? 'selected' : '' ?>>نشط</option>
                            <option value="inactive" <?= ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>غير نشط</option>
                            <option value="on_leave" <?= ($filters['status'] ?? '') == 'on_leave' ? 'selected' : '' ?>>في إجازة</option>
                            <option value="terminated" <?= ($filters['status'] ?? '') == 'terminated' ? 'selected' : '' ?>>متوقف</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">تطبيق</button>
                            <a href="<?= base_url('employees') ?>" class="btn btn-secondary">إعادة</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Employees Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-users me-2 text-primary"></i>
                    قائمة الموظفين
                </h5>
                <div>
                    <a href="<?= base_url('employees/export/excel') ?>" class="btn btn-sm btn-success me-2">
                        <i class="fas fa-file-excel me-1"></i>Excel
                    </a>
                    <a href="<?= base_url('employees/export/pdf') ?>" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf me-1"></i>PDF
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الموظف</th>
                                <th>الرقم الوظيفي</th>
                                <th>القسم</th>
                                <th>المسمى الوظيفي</th>
                                <th>الراتب</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $index => $emp): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($emp['photo']): ?>
                                        <img src="<?= upload_url('employees/' . $emp['photo']) ?>" class="rounded-circle me-2" width="40" height="40" alt="">
                                        <?php else: ?>
                                        <div class="user-avatar me-2" style="width: 40px; height: 40px;">
                                            <?= mb_substr($emp['full_name'], 0, 1) ?>
                                        </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold"><?= $emp['full_name'] ?></div>
                                            <small class="text-muted"><?= $emp['national_id'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $emp['employee_code'] ?></td>
                                <td><?= $emp['department_name'] ?: '-' ?></td>
                                <td><?= $emp['job_title'] ?></td>
                                <td><?= format_currency($emp['basic_salary']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $emp['status'] == 'active' ? 'success' : ($emp['status'] == 'on_leave' ? 'warning' : 'secondary') ?>">
                                        <?= get_status_arabic($emp['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('employees/view/' . $emp['id']) ?>" class="btn btn-sm btn-info me-1" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($auth->hasPermission('employees.edit')): ?>
                                    <a href="<?= base_url('employees/edit/' . $emp['id']) ?>" class="btn btn-sm btn-primary me-1" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($auth->hasPermission('employees.delete')): ?>
                                    <button class="btn btn-sm btn-danger" onclick="deleteEmployee(<?= $emp['id'] ?>)" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($employees)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>لا يوجد موظفين</p>
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

<script>
function deleteEmployee(id) {
    if (confirm('هل أنت متأكد من حذف هذا الموظف؟')) {
        fetch('<?= base_url('employees/delete/') ?>' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}
</script>

<?php $this->endSection(); ?>