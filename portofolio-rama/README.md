# 🎨 Portfolio Website - Rama Manansyah

## 📋 Deskripsi

Website portfolio profesional untuk Junior UI/UX Designer dengan integrated blog system. Dibangun dengan PHP, MySQL, dan vanilla JavaScript dengan design modern dan responsive.

---

## 📁 Struktur Folder

```
portofolio-rama/
│
├── 📄 Core Files (Root)
│   ├── index.php              # Homepage utama
│   ├── login.php              # Login & Registration page
│   ├── logout.php             # Logout handler
│   ├── admin-blog.php         # Admin panel untuk blog management
│   ├── save_post.php          # Handler untuk save blog posts
│   ├── delete_posts.php       # Handler untuk delete posts
│   ├── blog.php               # Blog listing page
│   ├── blog-detail.php        # Single blog post page
│   ├── config.php             # Database configuration
│   ├── styles.css             # Main stylesheet
│   ├── script.js              # Main JavaScript
│   ├── debug_system.php       # System diagnostic tool
│   └── auto_fix.php           # Auto-repair tool
│
├── 🖼️ Images (Root) - Static Assets
│   ├── Hero-image.jpg         # Hero section image
│   ├── About-image.jpg        # About section image
│   ├── Project-image.png      # Project preview image
│   └── login.jpg              # Login page background
│
├── 📂 uploads/                 # User-uploaded images (blog)
│   └── [timestamped images]
│
├── 📂 docs/                    # Documentation
│   ├── CHANGELOG_v2.0.md      # Version history
│   ├── IMAGE_GUIDELINES.md    # Image upload guidelines
│   ├── NEW_POLICY.md          # Admin policy documentation
│   ├── QUICK_START.md         # Quick start guide
│   └── TROUBLESHOOTING.md     # Troubleshooting guide
│
├── 📂 sql/                     # Database Scripts
│   ├── rama_blog.sql          # Main database schema
│   ├── fix_database.sql       # Fix script for role column
│   ├── migrate_to_all_admin.sql # Migration to v2.0
│   └── update_user_roles.sql  # Update user roles
│
├── 📂 tests/                   # Test & Debug Files
│   ├── debug_login.php        # Login debugging
│   ├── simple_login_test.php  # Simple login test
│   ├── test_form_submit.php   # Form submission test
│   ├── test_login.php         # Login functionality test
│   ├── fix_user_roles.php     # Role fix utility
│   ├── register_deprecated.php # Old registration file
│   └── contact_template.html  # Contact section template
│
├── 📂 assets/                  # Asset folders (for future use)
│   ├── css/
│   ├── js/
│   └── images/
│
└── 📂 admin/                   # Admin utilities (for future use)
```

---

## 🚀 Quick Start

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Installation

1. **Clone/Copy project ke htdocs:**
   ```
   C:\xampp\htdocs\portofolio-rama\
   ```

2. **Start XAMPP:**
   - Start Apache
   - Start MySQL

3. **Import Database:**
   ```
   - Buka phpMyAdmin
   - Create database: portofolio_db
   - Import: sql/rama_blog.sql
   ```

4. **Configure Database (optional):**
   ```php
   // Edit config.php jika perlu
   $host = 'localhost';
   $dbname = 'portofolio_db';
   $username = 'root';
   $password = '';
   ```

5. **Run Auto-Fix:**
   ```
   http://localhost/portofolio-rama/auto_fix.php
   ```

6. **Access Website:**
   ```
   http://localhost/portofolio-rama/
   ```

---

## 👤 User Management

### Registration & Login

**Policy Baru (v2.0):**
- ✅ **Semua user otomatis jadi ADMIN**
- ✅ Tidak ada pembedaan user pertama/kedua
- ✅ Full access ke blog management untuk semua

**Steps:**
1. Register di: `login.php` (tab Daftar)
2. Login dengan email & password
3. Otomatis redirect ke admin panel
4. Mulai mengelola blog

---

## 📝 Blog Management

### Features
- ✅ Create, Edit, Delete blog posts
- ✅ Upload images untuk artikel
- ✅ Rich text content
- ✅ Author attribution
- ✅ Timestamp tracking

### Admin Panel
```
URL: http://localhost/portofolio-rama/admin-blog.php
```

**Cara Menambah Post:**
1. Login sebagai admin
2. Buka admin-blog.php
3. Isi form:
   - Judul Artikel
   - Ringkasan
   - Konten Lengkap
   - Upload Gambar (opsional)
4. Klik "Simpan Artikel"

---

## 🛠️ Utilities & Tools

### Debug System
```
URL: http://localhost/portofolio-rama/debug_system.php
```
**Fungsi:**
- Cek database connection
- Verify users & roles
- Check session status
- View blog posts
- File permissions check
- Get recommendations

### Auto-Fix Tool
```
URL: http://localhost/portofolio-rama/auto_fix.php
```
**Fungsi:**
- Add missing role column
- Convert all users to admin
- Refresh session data
- Create uploads directory
- Verify database tables

---

## 📊 Database Schema

### Table: `users`
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Table: `blog_posts`
```sql
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT,
    content TEXT NOT NULL,
    image VARCHAR(255),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 🎨 Features

### Homepage
- ✅ Hero section dengan CTA
- ✅ About section dengan skills
- ✅ Services showcase
- ✅ Project portfolio
- ✅ Blog section (latest 6 posts)
- ✅ Contact information
- ✅ Smooth scrolling navigation

### Blog System
- ✅ Blog listing dengan pagination
- ✅ Single blog post view
- ✅ Related posts
- ✅ Author information
- ✅ Timestamps
- ✅ Image support

### Admin Panel
- ✅ CRUD operations untuk blog
- ✅ Image upload
- ✅ Session management
- ✅ Role-based access control

---

## 🔐 Security

### Implemented
- ✅ Password hashing (bcrypt)
- ✅ SQL injection prevention (prepared statements)
- ✅ Session management
- ✅ Role-based authorization
- ✅ Input validation
- ✅ Secure file uploads

---

## 📱 Responsive Design

### Breakpoints
- Desktop: 1200px+
- Tablet: 768px - 1199px
- Mobile: < 768px

### Optimizations
- ✅ Fluid typography (clamp)
- ✅ Flexible grids
- ✅ Touch-friendly buttons
- ✅ Mobile-first approach

---

## 🐛 Troubleshooting

### Common Issues

**1. Cannot login after register**
- ✅ By design - manual login required for security
- ✅ Use same email & password used for registration

**2. Access denied to admin panel**
- Run `auto_fix.php` to convert user to admin
- Logout and login again
- Check role in `debug_system.php`

**3. Images not uploading**
- Check uploads/ directory exists
- Verify directory permissions (777)
- Check file size (max 2-3MB recommended)

**4. Database connection error**
- Verify MySQL is running in XAMPP
- Check database name in config.php
- Ensure database is imported

### Get Help
1. Run `debug_system.php` for diagnostics
2. Run `auto_fix.php` for automatic repairs
3. Check `docs/TROUBLESHOOTING.md` for detailed guide

---

## 📚 Documentation

Lengkapi dokumentasi tersedia di folder `/docs`:

- **QUICK_START.md** - Panduan cepat 3 langkah
- **NEW_POLICY.md** - Policy admin & FAQ
- **TROUBLESHOOTING.md** - Panduan troubleshooting
- **IMAGE_GUIDELINES.md** - Panduan upload gambar
- **CHANGELOG_v2.0.md** - Version history

---

## 🔄 Version History

### v2.0.0 (Current)
- ✅ All users are admin policy
- ✅ Enhanced security (manual login)
- ✅ Auto-fix utility
- ✅ Comprehensive documentation

### v1.0.0
- ✅ Initial release
- ✅ Basic blog CMS
- ✅ First user as admin

---

## 🎯 Future Enhancements

### Planned Features
- [ ] User profile management
- [ ] Comment system
- [ ] Blog categories & tags
- [ ] Search functionality
- [ ] SEO optimization
- [ ] Social media sharing
- [ ] Newsletter integration
- [ ] Analytics dashboard

---

## 📞 Support

### Quick Commands

**Check System:**
```
http://localhost/portofolio-rama/debug_system.php
```

**Fix Issues:**
```
http://localhost/portofolio-rama/auto_fix.php
```

**Login/Register:**
```
http://localhost/portofolio-rama/login.php
```

**Admin Panel:**
```
http://localhost/portofolio-rama/admin-blog.php
```

---

## ⚖️ License

Personal Portfolio Project - All Rights Reserved

---

## 👨‍💻 Author

**Rama Manansyah**
- Role: Junior UI/UX Designer
- Portfolio: Specialized in modern web design

---

## 🙏 Credits

- Font: Inter & Poppins (Google Fonts)
- Icons: Font Awesome 6.4.0
- Framework: Vanilla PHP, MySQL, JavaScript
- Styling: Custom CSS with modern design principles

---

## 📝 Notes

### Important Files
- **config.php** - Database configuration (gitignore recommended)
- **uploads/** - User uploaded content (backup regularly)
- **sql/rama_blog.sql** - Main database schema

### Maintenance
- Backup database regularly
- Keep uploads/ folder backed up
- Monitor file uploads for size
- Update dependencies periodically

---

**Last Updated:** October 15, 2025
**Version:** 2.0.0
**Status:** ✅ Production Ready
