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
                <h5 class="card-title"><i class="fas fa-file-alt me-2 text-primary"></i>المستندات</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-1"></i>رفع مستند
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الموظف</th>
                                <th>العنوان</th>
                                <th>الفئة</th>
                                <th>تاريخ الرفع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $index => $doc): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $doc['employee_name'] ?></td>
                                <td><?= $doc['title'] ?></td>
                                <td><?= $doc['category'] ?></td>
                                <td><?= format_date($doc['created_at']) ?></td>
                                <td>
                                    <a href="<?= base_url('documents/download/' . $doc['id']) ?>" class="btn btn-sm btn-success me-1"><i class="fas fa-download"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteDocument(<?= $doc['id'] ?>)"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($documents)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <p>لا توجد مستندات</p>
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

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">رفع مستند جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('documents/upload') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الموظف</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">اختر الموظف</option>
                            <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= $emp['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">عنوان المستند</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الفئة</label>
                        <select name="category" class="form-select">
                            <option value="contract">عقد</option>
                            <option value="id">هوية</option>
                            <option value="certificate">شهادة</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الملف</label>
                        <input type="file" name="document" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">وصف</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">رفع</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteDocument(id) {
    if (confirm('هل أنت متأكد من حذف هذا المستند؟')) {
        fetch('<?= base_url('documents/delete/') ?>' + id, {
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