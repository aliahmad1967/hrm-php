<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <a href="<?= base_url('payroll') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>العودة للرواتب
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>طباعة
            </button>
        </div>
    </header>
    
    <div class="content">
        <div class="card">
            <div class="card-body p-5">
                <!-- Payslip Header -->
                <div class="text-center mb-4">
                    <h2>كشف الراتب</h2>
                    <p class="text-muted">Pay Slip</p>
                    <hr>
                </div>
                
                <!-- Employee Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>الموظف:</strong></td>
                                <td><?= $payroll['full_name'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>الرقم الوظيفي:</strong></td>
                                <td><?= $payroll['employee_code'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>المسمى الوظيفي:</strong></td>
                                <td><?= $payroll['job_title'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>القسم:</strong></td>
                                <td><?= $payroll['department_name'] ?: '-' ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>الفترة:</strong></td>
                                <td><?= format_date($payroll['pay_period_start']) ?> - <?= format_date($payroll['pay_period_end']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>تاريخ الإصدار:</strong></td>
                                <td><?= format_date(date('Y-m-d')) ?></td>
                            </tr>
                            <tr>
                                <td><strong>الحالة:</strong></td>
                                <td>
                                    <span class="badge bg-<?= $payroll['payment_status'] == 'paid' ? 'success' : 'warning' ?>">
                                        <?= get_status_arabic($payroll['payment_status']) ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <!-- Salary Details -->
                <div class="row">
                    <!-- Earnings -->
                    <div class="col-md-6">
                        <h5 class="mb-3">الاستحقاقات</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>الراتب الأساسي</td>
                                    <td class="text-start"><?= format_currency($payroll['basic_salary']) ?></td>
                                </tr>
                                <?php foreach ($items as $item): ?>
                                <?php if ($item['type'] == 'allowance'): ?>
                                <tr>
                                    <td><?= $item['name'] ?></td>
                                    <td class="text-start"><?= format_currency($item['amount']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($payroll['overtime_amount'] > 0): ?>
                                <tr>
                                    <td>الإضافي (<?= $payroll['overtime_hours'] ?> ساعة)</td>
                                    <td class="text-start"><?= format_currency($payroll['overtime_amount']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr class="table-success">
                                    <td><strong>الإجمالي</strong></td>
                                    <td class="text-start"><strong><?= format_currency($payroll['gross_salary']) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Deductions -->
                    <div class="col-md-6">
                        <h5 class="mb-3">الاستقطاعات</h5>
                        <table class="table">
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                <?php if ($item['type'] == 'deduction'): ?>
                                <tr>
                                    <td><?= $item['name'] ?></td>
                                    <td class="text-start"><?= format_currency($item['amount']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if (empty(array_filter($items, fn($i) => $i['type'] == 'deduction'))): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted">لا توجد استقطاعات</td>
                                </tr>
                                <?php endif; ?>
                                <tr class="table-danger">
                                    <td><strong>الإجمالي</strong></td>
                                    <td class="text-start"><strong><?= format_currency($payroll['total_deductions']) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <!-- Net Salary -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success text-center">
                            <h4>صافي الراتب</h4>
                            <h2 class="mb-0"><?= format_currency($payroll['net_salary']) ?></h2>
                        </div>
                    </div>
                </div>
                
                <!-- Notes -->
                <?php if ($payroll['notes']): ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h6>ملاحظات:</h6>
                        <p><?= $payroll['notes'] ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Footer -->
                <div class="text-center mt-5 pt-4 border-top">
                    <p class="text-muted">تم إنشاء هذا الكشف بواسطة نظام إدارة الموارد البشرية</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar, .header, .btn { display: none !important; }
    .main-content { margin-right: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>

<?php $this->endSection(); ?>