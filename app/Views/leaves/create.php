<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main-content" style="margin-right: 280px; min-height: 100vh; background: #f8fafc;">
    
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 40px 30px; margin-bottom: 30px; border-radius: 0 0 24px 24px; box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);">
        <div style="max-width: 1000px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 30px; color: white;">
                        <i class="fas fa-umbrella-beach"></i>
                    </div>
                    <div>
                        <h1 style="color: white; font-size: 32px; margin: 0 0 5px 0; font-weight: 700;">طلب إجازة جديد</h1>
                        <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 16px;">تقديم طلب إجازة للموظف</p>
                    </div>
                </div>
                <a href="<?= base_url('leaves') ?>" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; border: 2px solid rgba(255,255,255,0.3);">
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
                <div style="padding: 20px; border-radius: 12px; margin-bottom: 30px; font-weight: 500; display: flex; align-items: center; gap: 12px; <?= $flash['type'] === 'success' ? 'background: #dcfce7; color: #166534;' : 'background: #fee2e2; color: #991b1b;' ?>">
                    <i class="fas <?= $flash['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>" style="font-size: 24px;"></i>
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
                
                <!-- Card Header -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 25px 30px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 20px; color: #1e293b; font-weight: 700;">بيانات طلب الإجازة</h2>
                        <p style="margin: 4px 0 0 0; color: #64748b; font-size: 14px;">قم بملء البيانات التالية</p>
                    </div>
                </div>

                <!-- Form -->
                <form action="<?= base_url('leaves/store') ?>" method="POST" enctype="multipart/form-data" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <!-- Section 1: Employee & Type -->
                    <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #f1f5f9;">
                        <h3 style="color: #374151; font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #f59e0b; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-user"></i>
                            </span>
                            <span style="font-weight: 600;">الموظف ونوع الإجازة</span>
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-user" style="color: #f59e0b; margin-left: 6px;"></i>
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
                                    <i class="fas fa-tag" style="color: #f59e0b; margin-left: 6px;"></i>
                                    نوع الإجازة
                                    <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="leave_type" required style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; background: white; cursor: pointer;">
                                    <option value="">-- اختر النوع --</option>
                                    <option value="annual">إجازة سنوية</option>
                                    <option value="sick">إجازة مرضية</option>
                                    <option value="unpaid">إجازة بدون راتب</option>
                                    <option value="emergency">إجازة طارئة</option>
                                    <option value="maternity">إجازة أمومة</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Dates -->
                    <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #f1f5f9;">
                        <h3 style="color: #374151; font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #f59e0b; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <span style="font-weight: 600;">فترة الإجازة</span>
                        </h3>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-calendar" style="color: #10b981; margin-left: 6px;"></i>
                                    تاريخ البداية
                                    <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="date" name="start_date" required 
                                       style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px;">
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                    <i class="fas fa-calendar" style="color: #ef4444; margin-left: 6px;"></i>
                                    تاريخ النهاية
                                    <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="date" name="end_date" required 
                                       style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px;">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Reason -->
                    <div style="margin-bottom: 30px; padding-bottom: 30px; border-bottom: 2px solid #f1f5f9;">
                        <h3 style="color: #374151; font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #f59e0b; color: white; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-comment"></i>
                            </span>
                            <span style="font-weight: 600;">السبب والتفاصيل</span>
                        </h3>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                <i class="fas fa-edit" style="color: #f59e0b; margin-left: 6px;"></i>
                                سبب الإجازة
                                <span style="color: #ef4444;">*</span>
                            </label>
                            <textarea name="reason" rows="4" required placeholder="اكتب سبب طلب الإجازة..." 
                                      style="width: 100%; padding: 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px; resize: vertical; font-family: inherit;"></textarea>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 14px;">
                                <i class="fas fa-paperclip" style="color: #f59e0b; margin-left: 6px;"></i>
                                مرفقات (اختياري)
                            </label>
                            <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 15px;">
                            <p style="color: #6b7280; font-size: 13px; margin-top: 5px;">PDF, JPG, PNG, DOC - الحد الأقصى 5 ميجابايت</p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div style="display: flex; gap: 15px; padding-top: 30px; border-top: 2px dashed #e2e8f0;">
                        <button type="submit" style="flex: 1; padding: 16px 30px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 12px; font-size: 17px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4); display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="fas fa-paper-plane"></i>
                            تقديم الطلب
                        </button>
                        <a href="<?= base_url('leaves') ?>" style="padding: 16px 30px; background: #f1f5f9; color: #64748b; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 17px; font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
