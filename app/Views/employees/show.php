<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <a href="<?= base_url('employees') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
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
        
        <div class="row">
            <!-- Employee Info Card -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if ($employee['photo']): ?>
                        <img src="<?= upload_url('employees/' . $employee['photo']) ?>" class="rounded-circle mb-3" width="120" height="120" alt="">
                        <?php else: ?>
                        <div class="user-avatar mx-auto mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                            <?= mb_substr($employee['full_name'], 0, 1) ?>
                        </div>
                        <?php endif; ?>
                        <h4><?= $employee['full_name'] ?></h4>
                        <p class="text-muted"><?= $employee['job_title'] ?></p>
                        <span class="badge bg-<?= $employee['status'] == 'active' ? 'success' : 'secondary' ?>">
                            <?= get_status_arabic($employee['status']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title">معلومات التواصل</h6>
                    </div>
                    <div class="card-body">
                        <p><i class="fas fa-envelope me-2 text-primary"></i><?= $employee['email'] ?: '-' ?></p>
                        <p><i class="fas fa-phone me-2 text-primary"></i><?= $employee['phone'] ?: '-' ?></p>
                        <p><i class="fas fa-id-card me-2 text-primary"></i><?= $employee['national_id'] ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Employee Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#info">معلومات وظيفية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#attendance">الحضور</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#leaves">الإجازات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#payroll">الرواتب</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Job Info Tab -->
                            <div class="tab-pane fade show active" id="info">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%">الرقم الوظيفي:</td>
                                        <td><strong><?= $employee['employee_code'] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>القسم:</td>
                                        <td><?= $employee['department_name'] ?: '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td>المسمى الوظيفي:</td>
                                        <td><?= $employee['job_title'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>نوع العقد:</td>
                                        <td><?= get_employment_type_arabic($employee['employment_type']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>تاريخ التعيين:</td>
                                        <td><?= format_date($employee['hire_date']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>الراتب الأساسي:</td>
                                        <td><?= format_currency($employee['basic_salary']) ?></td>
                                    </tr>
                                    <tr>
                                        <td>تاريخ الميلاد:</td>
                                        <td><?= $employee['date_of_birth'] ? format_date($employee['date_of_birth']) . ' (' . calculate_age($employee['date_of_birth']) . ' سنة)' : '-' ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Attendance Tab -->
                            <div class="tab-pane fade" id="attendance">
                                <?php if (!empty($attendance)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>الدخول</th>
                                                <th>الخروج</th>
                                                <th>الحالة</th>
                                                <th>الساعات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($attendance, 0, 10) as $att): ?>
                                            <tr>
                                                <td><?= format_date($att['date']) ?></td>
                                                <td><?= $att['check_in'] ?: '-' ?></td>
                                                <td><?= $att['check_out'] ?: '-' ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $att['status'] == 'present' ? 'success' : ($att['status'] == 'late' ? 'warning' : ($att['status'] == 'absent' ? 'danger' : 'info')) ?>">
                                                        <?= get_status_arabic($att['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= $att['work_hours'] ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <p class="text-center text-muted py-4">لا توجد سجلات حضور</p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Leaves Tab -->
                            <div class="tab-pane fade" id="leaves">
                                <?php if (!empty($leaves)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>النوع</th>
                                                <th>من</th>
                                                <th>إلى</th>
                                                <th>الأيام</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($leaves, 0, 10) as $leave): ?>
                                            <tr>
                                                <td><?= get_leave_type_arabic($leave['leave_type']) ?></td>
                                                <td><?= format_date($leave['start_date']) ?></td>
                                                <td><?= format_date($leave['end_date']) ?></td>
                                                <td><?= $leave['days_count'] ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $leave['status'] == 'approved' ? 'success' : ($leave['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                                        <?= get_status_arabic($leave['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <p class="text-center text-muted py-4">لا توجد إجازات</p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Payroll Tab -->
                            <div class="tab-pane fade" id="payroll">
                                <?php if (!empty($payroll)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>الفترة</th>
                                                <th>الراتب الأساسي</th>
                                                <th>الإجمالي</th>
                                                <th>الصافي</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($payroll, 0, 10) as $pay): ?>
                                            <tr>
                                                <td><?= format_date($pay['pay_period_start']) ?> - <?= format_date($pay['pay_period_end']) ?></td>
                                                <td><?= format_currency($pay['basic_salary']) ?></td>
                                                <td><?= format_currency($pay['gross_salary']) ?></td>
                                                <td><?= format_currency($pay['net_salary']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $pay['payment_status'] == 'paid' ? 'success' : 'warning' ?>">
                                                        <?= get_status_arabic($pay['payment_status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <p class="text-center text-muted py-4">لا توجد سجلات رواتب</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>