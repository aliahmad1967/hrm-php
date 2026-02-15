<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <a href="<?= base_url('attendance') ?>" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="fas fa-clock me-2 text-primary"></i>تعديل سجل الحضور</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('attendance/update/' . $attendance['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الموظف</label>
                            <input type="text" class="form-control" value="<?= $attendance['full_name'] ?>" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">التاريخ</label>
                            <input type="date" name="date" class="form-control" value="<?= $attendance['date'] ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">وقت الدخول</label>
                            <input type="time" name="check_in" class="form-control" value="<?= $attendance['check_in'] ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">وقت الخروج</label>
                            <input type="time" name="check_out" class="form-control" value="<?= $attendance['check_out'] ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="present" <?= $attendance['status'] == 'present' ? 'selected' : '' ?>>حاضر</option>
                                <option value="absent" <?= $attendance['status'] == 'absent' ? 'selected' : '' ?>>غائب</option>
                                <option value="late" <?= $attendance['status'] == 'late' ? 'selected' : '' ?>>متأخر</option>
                                <option value="leave" <?= $attendance['status'] == 'leave' ? 'selected' : '' ?>>إجازة</option>
                                <option value="holiday" <?= $attendance['status'] == 'holiday' ? 'selected' : '' ?>>عطلة</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3"><?= $attendance['notes'] ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="<?= base_url('attendance') ?>" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>