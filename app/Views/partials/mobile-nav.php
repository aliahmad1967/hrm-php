<!-- Mobile Bottom Navigation -->
<nav class="mobile-nav d-md-none">
    <div class="mobile-nav-container">
        <a href="<?= base_url('dashboard') ?>" class="mobile-nav-item <?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>الرئيسية</span>
        </a>
        
        <a href="<?= base_url('employees') ?>" class="mobile-nav-item <?= strpos(current_url(), 'employees') !== false ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>الموظفين</span>
        </a>
        
        <a href="<?= base_url('attendance') ?>" class="mobile-nav-item <?= strpos(current_url(), 'attendance') !== false ? 'active' : '' ?>">
            <i class="fas fa-clock"></i>
            <span>الحضور</span>
        </a>
        
        <a href="<?= base_url('payroll') ?>" class="mobile-nav-item <?= strpos(current_url(), 'payroll') !== false ? 'active' : '' ?>">
            <i class="fas fa-money-bill-wave"></i>
            <span>الرواتب</span>
        </a>
        
        <a href="#" class="mobile-nav-item mobile-menu-toggle">
            <i class="fas fa-bars"></i>
            <span>المزيد</span>
        </a>
    </div>
</nav>

<!-- Mobile Sidebar Overlay -->
<div class="mobile-sidebar-overlay d-md-none"></div>

<style>
/* Mobile Bottom Navigation */
.mobile-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    z-index: 1030;
    border-top: 1px solid #e2e8f0;
}

.mobile-nav-container {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 8px 0;
}

.mobile-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: #64748b;
    font-size: 11px;
    padding: 4px 8px;
    min-width: 60px;
    transition: all 0.3s;
}

.mobile-nav-item i {
    font-size: 20px;
    margin-bottom: 4px;
}

.mobile-nav-item.active {
    color: #2563eb;
}

.mobile-nav-item:hover {
    color: #2563eb;
}

/* Adjust main content for mobile nav */
@media (max-width: 768px) {
    .main-content {
        margin-bottom: 70px;
    }
    
    .content {
        padding: 15px;
    }
}

/* Mobile Sidebar */
.mobile-sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s;
}

.mobile-sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Mobile header adjustments */
@media (max-width: 768px) {
    .header {
        padding: 0 15px;
    }
    
    .header-title {
        font-size: 1rem;
    }
    
    .user-info {
        display: none;
    }
    
    .sidebar {
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-right: 0;
    }
}
</style>

<script>
// Mobile menu toggle
document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelector('.sidebar').classList.toggle('show');
    document.querySelector('.mobile-sidebar-overlay').classList.toggle('show');
});

// Close sidebar when clicking overlay
document.querySelector('.mobile-sidebar-overlay')?.addEventListener('click', function() {
    document.querySelector('.sidebar').classList.remove('show');
    this.classList.remove('show');
});

// Close sidebar when clicking a link (on mobile)
document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            document.querySelector('.sidebar').classList.remove('show');
            document.querySelector('.mobile-sidebar-overlay').classList.remove('show');
        }
    });
});
</script>