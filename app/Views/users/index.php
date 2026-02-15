<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <div class="dropdown">
                <div class="user-menu" data-bs-toggle="dropdown">
                    <div class="user-avatar"><?= mb_substr($user['full_name'], 0, 1) ?></div>
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
    
    <div class="content">
        <?php if ($flash = flash()): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fas fa-users me-2 text-primary"></i>المستخدمين</h5>
                <a href="<?= base_url('users/create') ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i>إضافة مستخدم
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>اسم المستخدم</th>
                                <th>البريد</th>
                                <th>الدور</th>
                                <th>الحالة</th>
                                <th>آخر دخول</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $index => $u): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $u['full_name'] ?></td>
                                <td><?= $u['username'] ?></td>
                                <td><?= $u['email'] ?></td>
                                <td><?= $u['role_name'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $u['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $u['is_active'] ? 'نشط' : 'غير نشط' ?>
                                    </span>
                                </td>
                                <td><?= $u['last_login'] ? format_date($u['last_login']) : '-' ?></td>
                                <td>
                                    <a href="<?= base_url('users/edit/' . $u['id']) ?>" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i></a>
                                    <?php if ($u['id'] != $user['id']): ?>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $u['id'] ?>)"><i class="fas fa-trash"></i></button>
                                    <?php endif; ?>
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

<script>
function deleteUser(id) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        fetch('<?= base_url('users/delete/') ?>' + id, {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message);
        });
    }
}
</script>

<?php $this->endSection(); ?>