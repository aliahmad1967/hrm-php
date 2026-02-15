<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <?php if ($auth->hasPermission('departments.create')): ?>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>إضافة قسم
            </button>
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
        
        <div class="row">
            <?php foreach ($departments as $dept): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $dept['name_ar'] ?></h5>
                        <p class="text-muted"><?= $dept['description'] ?: 'لا يوجد وصف' ?></p>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-users me-2 text-primary"></i><?= $dept['employee_count'] ?> موظف</span>
                            <span><i class="fas fa-user-tie me-2 text-success"></i><?= $dept['manager_name'] ?: 'بدون مدير' ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex gap-2">
                            <?php if ($auth->hasPermission('departments.edit')): ?>
                            <button class="btn btn-sm btn-primary" onclick="editDept(<?= $dept['id'] ?>, '<?= $dept['name_ar'] ?>', '<?= $dept['description'] ?>')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php endif; ?>
                            <?php if ($auth->hasPermission('departments.delete')): ?>
                            <button class="btn btn-sm btn-danger" onclick="deleteDept(<?= $dept['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($departments)): ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-building fa-3x mb-3"></i>
                <p>لا توجد أقسام</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة قسم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('departments/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم القسم *</label>
                        <input type="text" name="name_ar" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الاسم بالإنجليزية</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteDept(id) {
    if (confirm('هل أنت متأكد من حذف هذا القسم؟')) {
        fetch('<?= base_url('departments/delete/') ?>' + id, {
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

function editDept(id, name, desc) {
    // For simplicity, redirect to edit page
    window.location.href = '<?= base_url('departments/edit/') ?>' + id;
}
</script>

<?php $this->endSection(); ?>