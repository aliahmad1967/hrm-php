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
        
        <div class="row">
            <!-- Backup Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-database me-2 text-primary"></i>
                            إنشاء نسخة احتياطية
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('backup/create') ?>" method="POST">
                            <?= csrf_field() ?>
                            <p class="text-muted mb-4">
                                سيتم إنشاء نسخة احتياطية كاملة تشمل:
                            </p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    قاعدة البيانات كاملة
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    صور الموظفين
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    المستندات المرفقة
                                </li>
                                <li>
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    ضغط وتشفير AES-256
                                </li>
                            </ul>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-download me-2"></i>
                                إنشاء نسخة احتياطية الآن
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Restore -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-undo me-2 text-warning"></i>
                            استعادة نسخة احتياطية
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('backup/restore') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">اختر ملف النسخة الاحتياطية</label>
                                <input type="file" name="backup_file" class="form-control" accept=".zip,.enc" required>
                                <small class="text-muted">يجب أن يكون الملف بصيغة ZIP أو ZIP.ENC</small>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>تحذير:</strong> ستستبدل جميع البيانات الحالية
                            </div>
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('هل أنت متأكد؟ سيتم استبدال جميع البيانات!')">
                                <i class="fas fa-undo me-2"></i>
                                استعادة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Backup List -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-history me-2 text-primary"></i>
                            النسخ الاحتياطية
                        </h5>
                        <span class="badge bg-secondary"><?= count($backups) ?> نسخة</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الملف</th>
                                        <th>الحجم</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($backups as $index => $backup): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <i class="fas fa-file-archive text-warning me-2"></i>
                                            <?= $backup['filename'] ?>
                                        </td>
                                        <td><?= $this->formatSize($backup['size']) ?></td>
                                        <td><?= format_date_arabic($backup['created_at']) ?></td>
                                        <td>
                                            <a href="<?= base_url('backup/download/' . $backup['filename']) ?>" class="btn btn-sm btn-success me-1" title="تحميل">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <?php if ($auth->hasPermission('backup.delete')): ?>
                                            <button class="btn btn-sm btn-danger" onclick="deleteBackup('<?= $backup['filename'] ?>')" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($backups)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-database fa-3x mb-3"></i>
                                            <p>لا توجد نسخ احتياطية</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Backup Settings -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-cog me-2 text-primary"></i>
                            إعدادات النسخ الاحتياطي
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('backup/settings') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="backup_enabled" name="backup_enabled" <?= ($settings['backup_enabled'] ?? '1') == '1' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="backup_enabled">
                                            تفعيل النسخ الاحتياطي التلقائي
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="backup_encryption" name="backup_encryption" <?= ($settings['backup_encryption'] ?? '1') == '1' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="backup_encryption">
                                            تشفير النسخ الاحتياطية (AES-256)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">تكرار النسخ</label>
                                    <select name="backup_frequency" class="form-select">
                                        <option value="daily" <?= ($settings['backup_frequency'] ?? 'daily') == 'daily' ? 'selected' : '' ?>>يومي</option>
                                        <option value="weekly" <?= ($settings['backup_frequency'] ?? '') == 'weekly' ? 'selected' : '' ?>>أسبوعي</option>
                                        <option value="monthly" <?= ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : '' ?>>شهري</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">وقت النسخ</label>
                                    <input type="time" name="backup_time" class="form-control" value="<?= $settings['backup_time'] ?? '02:00' ?>">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">فترة الاحتفاظ (يوم)</label>
                                    <input type="number" name="backup_retention_days" class="form-control" value="<?= $settings['backup_retention_days'] ?? '14' ?>" min="1" max="365">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                حفظ الإعدادات
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteBackup(filename) {
    if (confirm('هل أنت متأكد من حذف هذه النسخة الاحتياطية؟')) {
        fetch('<?= base_url('backup/delete/') ?>' + filename, {
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

<?php 
// Helper function to format file size
function formatSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $unitIndex = 0;
    while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
        $bytes /= 1024;
        $unitIndex++;
    }
    return round($bytes, 2) . ' ' . $units[$unitIndex];
}
?>

<?php $this->endSection(); ?>