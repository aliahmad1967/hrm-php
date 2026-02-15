<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <a href="<?= base_url('employees') ?>" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="fas fa-user-plus me-2 text-primary"></i>إضافة موظف جديد</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('employees/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="employee_code" value="<?= $employee_code ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم الكامل *</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الرقم الوظيفي</label>
                            <input type="text" class="form-control" value="<?= $employee_code ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الهوية *</label>
                            <input type="text" name="national_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الميلاد</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الجوال</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">القسم</label>
                            <select name="department_id" class="form-select">
                                <option value="">اختر القسم</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= $dept['name_ar'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المسمى الوظيفي *</label>
                            <input type="text" name="job_title" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع العقد</label>
                            <select name="employment_type" class="form-select">
                                <option value="full_time">دوام كامل</option>
                                <option value="part_time">دوام جزئي</option>
                                <option value="contract">عقد</option>
                                <option value="intern">متدرب</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الراتب الأساسي</label>
                            <input type="number" name="basic_salary" class="form-control" step="0.01">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ التعيين *</label>
                            <input type="date" name="hire_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ انتهاء العقد</label>
                            <input type="date" name="contract_end_date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">صورة شخصية</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="<?= base_url('employees') ?>" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>