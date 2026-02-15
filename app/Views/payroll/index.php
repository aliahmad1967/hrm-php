<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <?php if ($auth->hasPermission('payroll.create')): ?>
            <a href="<?= base_url('payroll/create') ?>" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>إنشاء كشف راتب
            </a>
            <?php endif; ?>
        </div>
    </header>
    
    <div class="content">
        <?php if ($flash = flash()): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= base_url('payroll') ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">الشهر</label>
                        <select name="month" class="form-select">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= sprintf('%02d', $i) ?>" <?= $month == sprintf('%02d', $i) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">السنة</label>
                        <select name="year" class="form-select">
                            <?php for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                            <option value="<?= $i ?>" <?= $year == $i ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الموظف</label>
                        <select name="employee_id" class="form-select">
                            <option value="">جميع الموظفين</option>
                            <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>" <?= ($employee_id ?? '') == $emp['id'] ? 'selected' : '' ?>><?= $emp['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">عرض</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Payroll Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">كشوف الرواتب</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>الفترة</th>
                                <th>الراتب الأساسي</th>
                                <th>الإجمالي</th>
                                <th>الصافي</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payroll as $pay): ?>
                            <tr>
                                <td><?= $pay['full_name'] ?></td>
                                <td><?= format_date($pay['pay_period_start']) ?> - <?= format_date($pay['pay_period_end']) ?></td>
                                <td><?= format_currency($pay['basic_salary']) ?></td>
                                <td><?= format_currency($pay['gross_salary']) ?></td>
                                <td><?= format_currency($pay['net_salary']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $pay['payment_status'] == 'paid' ? 'success' : 'warning' ?>">
                                        <?= get_status_arabic($pay['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('payroll/payslip/' . $pay['id']) ?>" class="btn btn-sm btn-info me-1" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <?php if ($auth->hasPermission('payroll.delete')): ?>
                                    <button class="btn btn-sm btn-danger" onclick="deletePayroll(<?= $pay['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($payroll)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    لا توجد كشوف رواتب
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deletePayroll(id) {
    if (confirm('هل أنت متأكد من حذف كشف الراتب؟')) {
        fetch('<?= base_url('payroll/delete/') ?>' + id, {
            method: 'POST',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert(data.message);
        });
    }
}
</script>

<?php $this->endSection(); ?>