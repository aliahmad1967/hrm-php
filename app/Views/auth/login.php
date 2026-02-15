<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<div class="login-page">
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-users-cog fa-3x mb-3"></i>
            <h1>نظام إدارة الموارد البشرية</h1>
            <p>تسجيل الدخول إلى النظام</p>
        </div>
        
        <div class="login-body">
            <?php if ($flash = flash()): ?>
                <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                    <?= $flash['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('auth/login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" placeholder="اسم المستخدم" required autofocus>
                    <label for="username">
                        <i class="fas fa-user me-2"></i>اسم المستخدم أو البريد الإلكتروني
                    </label>
                </div>
                
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="كلمة المرور" required>
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>كلمة المرور
                    </label>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            تذكرني
                        </label>
                    </div>
                    <a href="<?= base_url('auth/forgot-password') ?>" class="text-decoration-none">
                        نسيت كلمة المرور؟
                    </a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            <p class="text-muted">
                <small>جميع الحقوق محفوظة &copy; <?= date('Y') ?></small>
            </p>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>