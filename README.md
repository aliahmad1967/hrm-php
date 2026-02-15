# نظام إدارة الموارد البشرية
# HR Management System

<div dir="rtl">

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)](https://mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**نظام احترافي متكامل لإدارة الموارد البشرية باللغة العربية مع دعم كامل للاتجاه من اليمين لليسار (RTL)**

## 📋 نظرة عامة

نظام إدارة الموارد البشرية (HRM) هو تطبيق ويب متكامل مبني باستخدام PHP 8+ وMySQL/MariaDB، يوفر حلاً شاملاً لإدارة شؤون الموظفين والموارد البشرية في الشركات والمؤسسات.

### ✨ المميزات الرئيسية

- 🏢 **إدارة الموظفين** - سجلات كاملة للموظفين مع الصور والمستندات
- 🏛️ **إدارة الأقسام** - تنظيم هيكلي للشركة مع المديرين
- ⏰ **الحضور والانصراف** - تتبع يومي دقيق مع تقارير
- 📅 **الإجازات** - نظام طلبات وموافقات متكامل
- 💰 **الرواتب** - حساب الرواتب والبدلات والاستقطاعات
- 📄 **المستندات** - أرشيف إلكتروني للمستندات
- 💾 **النسخ الاحتياطي** - نسخ احتياطي مشفر واستعادة
- 👥 **إدارة المستخدمين** - نظام صلاحيات متعدد المستويات
- 📊 **لوحة التحكم** - إحصائيات ورسوم بيانية تفاعلية
- 🌐 **عربي RTL** - واجهة عربية بالكامل مع دعم RTL

</div>

---

## 🚀 Installation | التثبيت

### Requirements | المتطلبات

- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite enabled
- Web server (XAMPP, WAMP, LAMP, etc.)

### Quick Install | التثبيت السريع

1. **Clone the repository:**
```bash
git clone https://github.com/yourusername/hrm-system.git
cd hrm-system
```

2. **Create database:**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create database: `hrm_system`
   - Import: `database/schema.sql`

3. **Or use the install script:**
```bash
# Navigate to project folder in browser
http://localhost/hrm-php/install.php
```

4. **Configure database (if needed):**
   - Edit: `config/database.php`
   - Set your database credentials

5. **Access the system:**
```
http://localhost/hrm-php/
```

### Default Login | بيانات الدخول الافتراضية

- **Username:** `admin`
- **Password:** `admin123`

⚠️ **Important:** Change the default password immediately after first login!

---

## 📁 Project Structure | هيكل المشروع

```
hrm-php/
├── app/
│   ├── Controllers/      # All controllers
│   ├── Models/          # Database models
│   ├── Views/           # View templates (Arabic RTL)
│   ├── Database.php     # Database connection
│   ├── Auth.php         # Authentication system
│   ├── View.php         # View renderer
│   └── Router.php       # URL routing
├── config/
│   ├── app.php          # App configuration
│   └── database.php     # Database settings
├── database/
│   └── schema.sql       # Database schema
├── public/
│   └── assets/          # CSS, JS, uploads
├── helpers/
│   └── functions.php    # Helper functions
├── routes/
│   └── web.php          # Route definitions
├── index.php            # Entry point
├── install.php          # Installation script
└── README.md            # This file
```

---

## 🎯 Features Guide | دليل المميزات

### 1. Dashboard | لوحة التحكم
- View statistics and KPIs
- Attendance trends charts
- Pending leaves alerts
- Upcoming contract endings
- Recent employees

### 2. Employee Management | إدارة الموظفين
- Add/Edit/Delete employees
- Upload photos and documents
- View employee profiles
- Filter and search
- Export to PDF/Excel

### 3. Departments | الأقسام
- Manage company departments
- Assign department managers
- View employee count per department

### 4. Attendance | الحضور والانصراف
- Daily attendance tracking
- Present/Absent/Late/Leave status
- Monthly reports
- Working hours calculation

### 5. Leave Management | الإجازات
- Annual/Sick/Unpaid/Emergency leave types
- Approval workflow
- Leave balance tracking
- Export reports

### 6. Payroll | الرواتب
- Salary calculation
- Allowances and deductions
- Overtime calculation
- Payslip generation (PDF)
- Monthly payroll reports

### 7. Documents | المستندات
- Upload employee documents
- Categorize documents
- Secure file storage
- Download and preview

### 8. Backup & Restore | النسخ الاحتياطي
- Manual backup creation
- Automatic backup scheduling
- AES-256 encryption
- One-click restore
- Backup retention policy

### 9. User Management | إدارة المستخدمين
- Multi-role system (Admin, HR, Manager)
- Permission-based access control
- User activity tracking
- Password management

### 10. Settings | الإعدادات
- Company information
- Working hours and days
- Leave policies
- Backup configuration
- Currency settings

---

## 🔐 Security Features | مميزات الأمان

- ✅ Password hashing with bcrypt
- ✅ CSRF token protection
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ XSS filtering
- ✅ File upload validation
- ✅ AES-256 encryption for backups
- ✅ Role-based access control (RBAC)
- ✅ Session security

---

## 🛠️ Configuration | الإعدادات

### Database Configuration | إعدادات قاعدة البيانات

Edit `config/database.php`:

```php
return [
    'host' => 'localhost',
    'database' => 'hrm_system',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci'
];
```

### Application Configuration | إعدادات التطبيق

Edit `config/app.php`:

```php
return [
    'name' => 'نظام إدارة الموارد البشرية',
    'url' => 'http://localhost/hrm-php',
    'timezone' => 'Asia/Baghdad',
    'locale' => 'ar',
    'direction' => 'rtl',
    'currency' => 'IQD',  // Iraqi Dinar
];
```

---

## 📱 Screenshots | لقطات الشاشة

*(Add screenshots here)*

---

## 🐛 Troubleshooting | حل المشاكل

### Issue: Blank page
**Solution:** Check PHP error logs and ensure all required extensions are enabled.

### Issue: Database connection failed
**Solution:** 
1. Verify MySQL is running
2. Check database credentials in `config/database.php`
3. Ensure database `hrm_system` exists

### Issue: 404 errors
**Solution:** Enable `mod_rewrite` in Apache and ensure `.htaccess` file exists.

### Issue: Permission denied
**Solution:** Set correct permissions:
```bash
chmod -R 755 public/assets/uploads
chmod -R 755 public/backups
chmod -R 755 storage/logs
```

### Issue: Login not working
**Solution:** Run `fix_pwd.php` to reset admin password:
```
http://localhost/hrm-php/fix_pwd.php
```

---

## 🧪 Testing | الاختبار

Run comprehensive system test:
```
http://localhost/hrm-php/test_all.php
```

---

## 📝 API Documentation | توثيق API

### Available Endpoints | نقاط النهاية المتاحة

```
GET/POST  /auth/login          - Login
GET       /auth/logout         - Logout
GET       /dashboard           - Dashboard
GET       /employees           - List employees
GET/POST  /employees/create    - Create employee
GET/POST  /employees/edit/{id} - Edit employee
GET       /employees/view/{id} - View employee
POST      /employees/delete/{id} - Delete employee
GET       /departments         - List departments
GET       /attendance          - Attendance tracking
GET       /leaves              - Leave requests
GET       /payroll             - Payroll management
GET       /documents           - Documents
GET       /backup              - Backup management
GET       /users               - User management
GET       /settings            - System settings
```

---

## 🤝 Contributing | المساهمة

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

---

## 📄 License | الترخيص

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author | المؤلف

**HR Management System Team**

For support or inquiries, please open an issue on GitHub.

---

## 🙏 Acknowledgments | الشكر

- Bootstrap 5 RTL Team
- Font Awesome
- Chart.js
- All open source contributors

---

<div dir="rtl">

## ⚡ ملاحظات سريعة

- النظام مصمم للعمل على localhost (محلي)
- للاستخدام في الإنتاج، تأكد من تفعيل HTTPS
- قم بتغيير كلمة المرور الافتراضية فوراً
- قم بإعداد النسخ الاحتياطي التلقائي
- تحقق من صلاحيات المجلدات

## 📞 الدعم

للحصول على المساعدة:
- افتح issue على GitHub
- راسلنا عبر البريد الإلكتروني
- شاركنا بتعليقاتك لتحسين النظام

**شكراً لاستخدام نظام إدارة الموارد البشرية!**

</div>

---

<p align="center">Made with ❤️ for the HR community</p>