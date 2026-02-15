<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <a href="<?= base_url('departments') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة
            </a>
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
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-building me-2 text-primary"></i>تعديل قسم</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('departments/update/' . $department['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم القسم *</label>
                            <input type="text" name="name_ar" class="form-control" value="<?= $department['name_ar'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم بالإنجليزية</label>
                            <input type="text" name="name" class="form-control" value="<?= $department['name'] ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المدير</label>
                            <select name="manager_id" class="form-select">
                                <option value="">بدون مدير</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>" <?= $department['manager_id'] == $emp['id'] ? 'selected' : '' ?>>
                                    <?= $emp['full_name'] ?> (<?= $emp['employee_code'] ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="4"><?= $department['description'] ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="<?= base_url('departments') ?>" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>