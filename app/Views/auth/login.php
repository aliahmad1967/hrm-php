<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<div class="login-wrapper">
    <div class="login-bg">
        <div class="login-bg-pattern"></div>
        <div class="login-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
    </div>
    
    <div class="login-content">
        <div class="login-card">
            <div class="login-card-header">
                <div class="login-logo">
                    <div class="logo-icon-wrapper">
                        <i class="fas fa-users-cog"></i>
                    </div>
                </div>
                <h1 class="login-title">مرحباً بعودتك</h1>
                <p class="login-subtitle">قم بتسجيل الدخول للوصول إلى لوحة التحكم</p>
            </div>
            
            <div class="login-card-body">
                <?php if ($flash = flash()): ?>
                    <div class="alert alert-<?= $flash['type'] ?> alert-custom fade show" role="alert">
                        <i class="fas <?= $flash['type'] === 'success' ? 'fa-check-circle' : ($flash['type'] === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle') ?> me-2"></i>
                        <?= $flash['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('auth/login') ?>" method="POST" class="login-form">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label class="form-label" for="username">
                            <i class="fas fa-user-circle"></i>
                            اسم المستخدم أو البريد الإلكتروني
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control custom-input" id="username" name="username" placeholder="أدخل اسم المستخدم" required autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password">
                            <i class="fas fa-lock"></i>
                            كلمة المرور
                        </label>
                        <div class="input-group password-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" class="form-control custom-input" id="password" name="password" placeholder="أدخل كلمة المرور" required>
                            <button type="button" class="btn btn-toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="form-check custom-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                <span class="check-icon"><i class="fas fa-check"></i></span>
                                تذكرني
                            </label>
                        </div>
                        <a href="<?= base_url('auth/forgot-password') ?>" class="forgot-link">
                            نسيت كلمة المرور؟
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-login-main">
                        <span class="btn-text">تسجيل الدخول</span>
                        <span class="btn-icon">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                    </button>
                </form>
            </div>
            
            <div class="login-card-footer">
                <p class="copyright">
                    جميع الحقوق محفوظة &copy; <?= date('Y') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        position: relative;
        overflow: hidden;
    }
    
    .login-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        z-index: 0;
    }
    
    .login-bg-pattern {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
    }
    
    .login-bg-shapes .shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.5;
        animation: float 20s infinite ease-in-out;
    }
    
    .shape-1 {
        width: 400px;
        height: 400px;
        background: #667eea;
        top: -100px;
        right: -100px;
        animation-delay: 0s;
    }
    
    .shape-2 {
        width: 300px;
        height: 300px;
        background: #f093fb;
        bottom: -50px;
        left: -50px;
        animation-delay: -5s;
    }
    
    .shape-3 {
        width: 200px;
        height: 200px;
        background: #764ba2;
        top: 50%;
        left: 30%;
        animation-delay: -10s;
    }
    
    .shape-4 {
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.3);
        top: 20%;
        right: 20%;
        animation-delay: -15s;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25% { transform: translate(30px, -30px) scale(1.1); }
        50% { transform: translate(-20px, 20px) scale(0.9); }
        75% { transform: translate(20px, 10px) scale(1.05); }
    }
    
    .login-content {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        z-index: 1;
    }
    
    .login-card {
        width: 100%;
        max-width: 440px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        overflow: hidden;
        animation: slideUp 0.6s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-card-header {
        text-align: center;
        padding: 40px 40px 20px;
    }
    
    .login-logo {
        margin-bottom: 24px;
    }
    
    .logo-icon-wrapper {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4); }
        50% { box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6); }
    }
    
    .logo-icon-wrapper i {
        font-size: 36px;
        color: white;
    }
    
    .login-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }
    
    .login-subtitle {
        font-size: 15px;
        color: #64748b;
        margin: 0;
    }
    
    .login-card-body {
        padding: 0 40px 30px;
    }
    
    .alert-custom {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 24px;
        font-size: 14px;
    }
    
    .alert-custom.alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    
    .alert-custom.alert-error {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .alert-custom.alert-info {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }
    
    .login-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .form-label i {
        color: #667eea;
        font-size: 14px;
    }
    
    .input-group {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }
    
    .input-group:focus-within {
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }
    
    .input-group-text {
        background: #f3f4f6;
        border: 2px solid #e5e7eb;
        border-left: none;
        color: #9ca3af;
        padding: 14px 16px;
        font-size: 16px;
    }
    
    .custom-input {
        border: 2px solid #e5e7eb;
        border-right: none;
        padding: 14px 16px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .custom-input:focus {
        border-color: #667eea;
        box-shadow: none;
    }
    
    .password-group {
        position: relative;
    }
    
    .btn-toggle-password {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 4px 8px;
        transition: color 0.3s;
        z-index: 10;
    }
    
    .btn-toggle-password:hover {
        color: #667eea;
    }
    
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }
    
    .custom-check {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    
    .custom-check .form-check-input {
        display: none;
    }
    
    .custom-check .form-check-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 14px;
        color: #4b5563;
        margin: 0;
        user-select: none;
    }
    
    .check-icon {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .check-icon i {
        font-size: 10px;
        color: white;
        opacity: 0;
        transform: scale(0);
        transition: all 0.2s ease;
    }
    
    .custom-check .form-check-input:checked + .form-check-label .check-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
    }
    
    .custom-check .form-check-input:checked + .form-check-label .check-icon i {
        opacity: 1;
        transform: scale(1);
    }
    
    .forgot-link {
        font-size: 14px;
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    
    .forgot-link:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    .btn-login-main {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 10px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .btn-login-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }
    
    .btn-login-main:active {
        transform: translateY(0);
    }
    
    .btn-icon {
        display: flex;
        align-items: center;
        transition: transform 0.3s ease;
    }
    
    .btn-login-main:hover .btn-icon {
        transform: translateX(-4px);
    }
    
    .login-card-footer {
        padding: 20px 40px;
        text-align: center;
        border-top: 1px solid #f3f4f6;
    }
    
    .copyright {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
    }
    
    /* Responsive */
    @media (max-width: 480px) {
        .login-card {
            border-radius: 20px;
        }
        
        .login-card-header {
            padding: 30px 24px 20px;
        }
        
        .login-card-body {
            padding: 0 24px 24px;
        }
        
        .login-card-footer {
            padding: 16px 24px;
        }
        
        .login-title {
            font-size: 24px;
        }
        
        .logo-icon-wrapper {
            width: 64px;
            height: 64px;
        }
        
        .logo-icon-wrapper i {
            font-size: 28px;
        }
    }
</style>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

<?php $this->endSection(); ?>
