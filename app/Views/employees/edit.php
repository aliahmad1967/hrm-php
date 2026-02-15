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
                <h5 class="card-title"><i class="fas fa-user-edit me-2 text-primary"></i>تعديل بيانات الموظف</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('employees/update/' . $employee['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم الكامل *</label>
                            <input type="text" name="full_name" class="form-control" value="<?= $employee['full_name'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الرقم الوظيفي</label>
                            <input type="text" class="form-control" value="<?= $employee['employee_code'] ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الهوية *</label>
                            <input type="text" name="national_id" class="form-control" value="<?= $employee['national_id'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الميلاد</label>
                            <input type="date" name="date_of_birth" class="form-control" value="<?= $employee['date_of_birth'] ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="<?= $employee['email'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الجوال</label>
                            <input type="text" name="phone" class="form-control" value="<?= $employee['phone'] ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">القسم</label>
                            <select name="department_id" class="form-select">
                                <option value="">اختر القسم</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $employee['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= $dept['name_ar'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المسمى الوظيفي *</label>
                            <input type="text" name="job_title" class="form-control" value="<?= $employee['job_title'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع العقد</label>
                            <select name="employment_type" class="form-select">
                                <option value="full_time" <?= $employee['employment_type'] == 'full_time' ? 'selected' : '' ?>>دوام كامل</option>
                                <option value="part_time" <?= $employee['employment_type'] == 'part_time' ? 'selected' : '' ?>>دوام جزئي</option>
                                <option value="contract" <?= $employee['employment_type'] == 'contract' ? 'selected' : '' ?>>عقد</option>
                                <option value="intern" <?= $employee['employment_type'] == 'intern' ? 'selected' : '' ?>>متدرب</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= $employee['status'] == 'active' ? 'selected' : '' ?>>نشط</option>
                                <option value="inactive" <?= $employee['status'] == 'inactive' ? 'selected' : '' ?>>غير نشط</option>
                                <option value="on_leave" <?= $employee['status'] == 'on_leave' ? 'selected' : '' ?>>في إجازة</option>
                                <option value="terminated" <?= $employee['status'] == 'terminated' ? 'selected' : '' ?>>متوقف</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الراتب الأساسي</label>
                            <input type="number" name="basic_salary" class="form-control" value="<?= $employee['basic_salary'] ?>" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ التعيين *</label>
                            <input type="date" name="hire_date" class="form-control" value="<?= $employee['hire_date'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">صورة شخصية</label>
                        <?php if ($employee['photo']): ?>
                        <div class="mb-2">
                            <img src="<?= upload_url('employees/' . $employee['photo']) ?>" width="100" class="rounded">
                        </div>
                        <?php endif; ?>
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