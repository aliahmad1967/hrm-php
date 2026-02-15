<?php $this->extend('main'); ?>
<?php $this->section('content'); ?>

<div class="text-center py-5">
    <h1 class="display-1 text-muted">404</h1>
    <h2 class="mb-4">الصفحة غير موجودة</h2>
    <p class="text-muted mb-4">عذراً، الصفحة التي تبحث عنها غير موجودة</p>
    <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">
        <i class="fas fa-home me-2"></i>
        العودة للرئيسية
    </a>
</div>

<?php $this->endSection(); ?>