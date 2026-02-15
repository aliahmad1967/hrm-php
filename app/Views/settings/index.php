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
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-cog me-2 text-primary"></i>إعدادات النظام</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('settings/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <?php foreach ($settings as $setting): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= $setting['description'] ?: $setting['key'] ?></label>
                            <input type="text" name="settings[<?= $setting['key'] ?>]" class="form-control" value="<?= $setting['value'] ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ الإعدادات
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>