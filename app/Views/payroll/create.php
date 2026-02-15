<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content">
    <header class="header">
        <h1 class="header-title"><?= $title ?></h1>
        <div class="header-actions">
            <a href="<?= base_url('payroll') ?>" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="fas fa-money-bill-wave me-2 text-primary"></i>إنشاء كشف راتب جديد</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('payroll/store') ?>" method="POST" id="payrollForm">
                    <?= csrf_field() ?>
                    
                    <!-- Employee & Period -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">الموظف *</label>
                            <select name="employee_id" class="form-select" id="employeeSelect" required>
                                <option value="">اختر الموظف</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>" data-salary="<?= $emp['basic_salary'] ?>">
                                    <?= $emp['full_name'] ?> - <?= $emp['employee_code'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">بداية الفترة *</label>
                            <input type="date" name="pay_period_start" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">نهاية الفترة *</label>
                            <input type="date" name="pay_period_end" class="form-control" required>
                        </div>
                    </div>
                    
                    <!-- Basic Salary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">الراتب الأساسي</label>
                            <input type="number" name="basic_salary" id="basicSalary" class="form-control" step="0.01" readonly>
                        </div>
                    </div>
                    
                    <!-- Allowances -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">البدلات</h6>
                        </div>
                        <div class="card-body">
                            <div id="allowancesContainer">
                                <div class="row allowance-row mb-2">
                                    <div class="col-md-5">
                                        <input type="text" name="allowances[0][name]" class="form-control" placeholder="اسم البدل">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" name="allowances[0][amount]" class="form-control allowance-amount" placeholder="المبلغ" step="0.01" value="0">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" onclick="addAllowance()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Deductions -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">الاستقطاعات</h6>
                        </div>
                        <div class="card-body">
                            <div id="deductionsContainer">
                                <div class="row deduction-row mb-2">
                                    <div class="col-md-5">
                                        <input type="text" name="deductions[0][name]" class="form-control" placeholder="اسم الاستقطاع">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" name="deductions[0][amount]" class="form-control deduction-amount" placeholder="المبلغ" step="0.01" value="0">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" onclick="addDeduction()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Overtime -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">ساعات العمل الإضافي</label>
                            <input type="number" name="overtime_hours" id="overtimeHours" class="form-control" step="0.5" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">معدل الساعة الإضافية</label>
                            <input type="number" name="overtime_rate" id="overtimeRate" class="form-control" step="0.01" value="0">
                        </div>
                    </div>
                    
                    <!-- Summary -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">إجمالي البدلات</label>
                                    <input type="text" id="totalAllowances" class="form-control" readonly value="0 د.ع">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">إجمالي الاستقطاعات</label>
                                    <input type="text" id="totalDeductions" class="form-control" readonly value="0 د.ع">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">إجمالي الراتب</label>
                                    <input type="text" id="grossSalary" class="form-control" readonly value="0 د.ع">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">صافي الراتب</label>
                                    <input type="text" id="netSalary" class="form-control fw-bold text-success" readonly value="0 د.ع">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ كشف الراتب
                        </button>
                        <a href="<?= base_url('payroll') ?>" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let allowanceCount = 1;
let deductionCount = 1;

// Auto-fill salary when employee is selected
document.getElementById('employeeSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const salary = selectedOption.getAttribute('data-salary');
    document.getElementById('basicSalary').value = salary || 0;
    calculateTotals();
});

function addAllowance() {
    const container = document.getElementById('allowancesContainer');
    const row = document.createElement('div');
    row.className = 'row allowance-row mb-2';
    row.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="allowances[${allowanceCount}][name]" class="form-control" placeholder="اسم البدل">
        </div>
        <div class="col-md-5">
            <input type="number" name="allowances[${allowanceCount}][amount]" class="form-control allowance-amount" placeholder="المبلغ" step="0.01" value="0" onchange="calculateTotals()">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
    allowanceCount++;
}

function addDeduction() {
    const container = document.getElementById('deductionsContainer');
    const row = document.createElement('div');
    row.className = 'row deduction-row mb-2';
    row.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="deductions[${deductionCount}][name]" class="form-control" placeholder="اسم الاستقطاع">
        </div>
        <div class="col-md-5">
            <input type="number" name="deductions[${deductionCount}][amount]" class="form-control deduction-amount" placeholder="المبلغ" step="0.01" value="0" onchange="calculateTotals()">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    container.appendChild(row);
    deductionCount++;
}

function removeRow(btn) {
    btn.closest('.row').remove();
    calculateTotals();
}

function calculateTotals() {
    const basicSalary = parseFloat(document.getElementById('basicSalary').value) || 0;
    const overtimeHours = parseFloat(document.getElementById('overtimeHours').value) || 0;
    const overtimeRate = parseFloat(document.getElementById('overtimeRate').value) || 0;
    
    // Calculate allowances
    let totalAllowances = 0;
    document.querySelectorAll('.allowance-amount').forEach(input => {
        totalAllowances += parseFloat(input.value) || 0;
    });
    
    // Calculate deductions
    let totalDeductions = 0;
    document.querySelectorAll('.deduction-amount').forEach(input => {
        totalDeductions += parseFloat(input.value) || 0;
    });
    
    // Calculate overtime
    const overtimeAmount = overtimeHours * overtimeRate;
    
    // Calculate totals
    const grossSalary = basicSalary + totalAllowances + overtimeAmount;
    const netSalary = grossSalary - totalDeductions;
    
    // Update display
    document.getElementById('totalAllowances').value = totalAllowances.toLocaleString() + ' د.ع';
    document.getElementById('totalDeductions').value = totalDeductions.toLocaleString() + ' د.ع';
    document.getElementById('grossSalary').value = grossSalary.toLocaleString() + ' د.ع';
    document.getElementById('netSalary').value = netSalary.toLocaleString() + ' د.ع';
}

// Calculate on page load
calculateTotals();

// Add listeners to initial inputs
document.querySelectorAll('.allowance-amount, .deduction-amount, #overtimeHours, #overtimeRate').forEach(input => {
    input.addEventListener('change', calculateTotals);
    input.addEventListener('keyup', calculateTotals);
});
</script>

<?php $this->endSection(); ?>