<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content" style="margin-right: 280px; min-height: 100vh; background: #f8fafc;">
    
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 40px 30px; margin-bottom: 30px; border-radius: 0 0 24px 24px; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
        <div style="max-width: 1000px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 30px; color: white;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h1 style="color: white; font-size: 32px; margin: 0 0 5px 0; font-weight: 700;">تسجيل حضور جديد</h1>
                        <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 16px;">إضافة سجل حضور للموظف</p>
                    </div>
                </div>
                <a href="<?= base_url('attendance') ?>" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; border: 2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div style="padding: 0 30px 40px;">
        <div style="max-width: 900px; margin: 0 auto;">
            
            <!-- Messages -->
            <?php if ($flash = flash()): ?>
                <div style="padding: 20px; border-radius: 12px; margin-bottom: 30px; font-weight: 500; display: flex; align-items: center; gap: 12px;">
                    <?php if ($flash['type'] === 'success'): ?>
                        <div style="background: #dcfce7; color: #166534;">
                            <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                            <?= $flash['message'] ?>
                        </div>
                    <?php else: ?>
                        <div style="background: #fee2e2; color: #991b1b;">
                            <i class="fas fa-exclamation-circle" style="font-size: 24px;"></i>
                            <?= $flash['message'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                
                <!-- Card Header -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 25px 30px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 20px; color: #1e293b; font-weight: 700;">بيانات الحضور</h2>
                        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 14px;">قم بإدخال بيانات الحضور بدقة</p>
                    </div>
                </div>

                <!-- Form -->
                <form action="<?= base_url('attendance/store') ?>" method="POST" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <!-- Section 1: Employee & Date -->
                    <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #f1f5f9;">
                        <h3 style="color: #374151; font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #6366f1; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-user-clock"></i>
                            </span>
                            <span style="font-weight: 600;">اختيار الموظف والتاريخ</span>
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-user" style="color: #6366f1; margin-left: 6px;"></i>
                                    الموظف
                                    <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="employee_id" required style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: white; cursor: pointer;">
                                    <option value="">-- اختر الموظف --</option>
                                    <?php if (!empty($employees)): ?>
                                        <?php foreach ($employees as $employee): ?>
                                            <option value="<?= $employee['id'] ?>">
                                                <?= $employee['full_name'] ?> - <?= $employee['employee_code'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-calendar-alt" style="color: #6366f1; margin-left: 6px;"></i>
                                    التاريخ
                                    <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="date" name="date" value="<?= date('Y-m-d') ?>" required 
                                       style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px;">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Times -->
                    <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #f1f5f9;">
                        <h3 style="color: #374151; font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #6366f1; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-clock"></i>
                            </span>
                            <span style="font-weight: 600;">أوقات الدخول والخروج</span>
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-sign-in-alt" style="color: #10b981; margin-left: 6px;"></i>
                                    وقت الدخول
                                </label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="time" name="check_in" style="flex: 1; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; font-family: monospace;">
                                    <button type="button" onclick="setTime('check_in')" style="padding: 14px 20px; background: #6366f1; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; white-space: nowrap;">
                                        <i class="fas fa-clock"></i> الآن
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-sign-out-alt" style="color: #ef4444; margin-left: 6px;"></i>
                                    وقت الخروج
                                </label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="time" name="check_out" style="flex: 1; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; font-family: monospace;">
                                    <button type="button" onclick="setTime('check_out')" style="padding: 14px 20px; background: #6366f1; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; white-space: nowrap;">
                                        <i class="fas fa-clock"></i> الآن
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Status -->
                    <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #f1f5f9;">
                        <h3 style="color: #374151; font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #6366f1; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-tasks"></i>
                            </span>
                            <span style="font-weight: 600;">حالة الحضور</span>
                            <span style="color: #ef4444;">*</span>
                        </h3>

                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                            <label style="cursor: pointer;">
                                <input type="radio" name="status" value="present" checked style="display: none;">
                                <div class="status-card" data-status="present" style="padding: 20px; border-radius: 16px; border: 2px solid #22c55e; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); text-align: center; transition: all 0.3s;">
                                    <div style="font-size: 28px; margin-bottom: 8px;">
                                        <i class="fas fa-user-check" style="color: #16a34a;"></i>
                                    </div>
                                    <div style="font-weight: 700; color: #166534; font-size: 15px;">حاضر</div>
                                    <div style="font-size: 12px; color: #22c55e; margin-top: 4px;">حضور في الوقت</div>
                                </div>
                            </label>

                            <label style="cursor: pointer;">
                                <input type="radio" name="status" value="late" style="display: none;">
                                <div class="status-card" data-status="late" style="padding: 20px; border-radius: 16px; border: 2px solid #e2e8f0; background: white; text-align: center; transition: all 0.3s;">
                                    <div style="font-size: 28px; margin-bottom: 8px;">
                                        <i class="fas fa-user-clock" style="color: #f59e0b;"></i>
                                    </div>
                                    <div style="font-weight: 700; color: #374151; font-size: 15px;">متأخر</div>
                                    <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">تأخر عن الدوام</div>
                                </div>
                            </label>

                            <label style="cursor: pointer;">
                                <input type="radio" name="status" value="absent" style="display: none;">
                                <div class="status-card" data-status="absent" style="padding: 20px; border-radius: 16px; border: 2px solid #e2e8f0; background: white; text-align: center; transition: all 0.3s;">
                                    <div style="font-size: 28px; margin-bottom: 8px;">
                                        <i class="fas fa-user-times" style="color: #ef4444;"></i>
                                    </div>
                                    <div style="font-weight: 700; color: #374151; font-size: 15px;">غائب</div>
                                    <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">غياب عن الدوام</div>
                                </div>
                            </label>

                            <label style="cursor: pointer;">
                                <input type="radio" name="status" value="leave" style="display: none;">
                                <div class="status-card" data-status="leave" style="padding: 20px; border-radius: 16px; border: 2px solid #e2e8f0; background: white; text-align: center; transition: all 0.3s;">
                                    <div style="font-size: 28px; margin-bottom: 8px;">
                                        <i class="fas fa-umbrella-beach" style="color: #3b82f6;"></i>
                                    </div>
                                    <div style="font-weight: 700; color: #374151; font-size: 15px;">إجازة</div>
                                    <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">إجازة رسمية</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Section 4: Notes -->
                    <div style="margin-bottom: 30px;">
                        <h3 style="color: #374151; font-size: 16px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #6366f1; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-sticky-note"></i>
                            </span>
                            <span style="font-weight: 600;">ملاحظات إضافية</span>
                        </h3>
                        <textarea name="notes" rows="4" placeholder="أضف أي ملاحظات أو تفاصيل إضافية..." style="width: 100%; padding: 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; resize: vertical; font-family: inherit;"></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div style="display: flex; gap: 15px; padding-top: 30px; border-top: 2px dashed #e2e8f0;">
                        <button type="submit" style="flex: 1; padding: 16px 30px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 12px; font-size: 17px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="fas fa-save"></i>
                            حفظ سجل الحضور
                        </button>
                        <a href="<?= base_url('attendance') ?>" style="padding: 16px 30px; background: #f1f5f9; color: #64748b; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 17px; font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function setTime(fieldName) {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.querySelector('input[name="' + fieldName + '"]').value = hours + ':' + minutes;
}

// Status card selection
document.querySelectorAll('input[name="status"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        // Reset all cards
        document.querySelectorAll('.status-card').forEach(function(card) {
            card.style.border = '2px solid #e2e8f0';
            card.style.background = 'white';
        });
        
        // Style selected card
        var selectedCard = this.parentElement.querySelector('.status-card');
        var status = this.value;
        
        if (status === 'present') {
            selectedCard.style.border = '2px solid #22c55e';
            selectedCard.style.background = 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)';
        } else if (status === 'late') {
            selectedCard.style.border = '2px solid #f59e0b';
            selectedCard.style.background = 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)';
        } else if (status === 'absent') {
            selectedCard.style.border = '2px solid #ef4444';
            selectedCard.style.background = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)';
        } else if (status === 'leave') {
            selectedCard.style.border = '2px solid #3b82f6';
            selectedCard.style.background = 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)';
        }
    });
});
</script>

<?php $this->endSection(); ?>
