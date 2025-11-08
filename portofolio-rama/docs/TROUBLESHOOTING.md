# 🔧 Troubleshooting Guide - Login & Blog System

## 🐛 Masalah yang Sering Terjadi

### **1. User tidak bisa menambah konten blog setelah register**

#### Penyebab:
- ❌ Column `role` tidak ada di tabel `users`
- ❌ Session tidak memiliki data `role` atau `id`
- ❌ Belum login setelah register
- ❌ Session outdated (registered sebelum policy baru)

#### Solusi:

**A. Jalankan Auto Fix (REKOMENDASI)**
```
http://localhost/portofolio-rama/auto_fix.php
```
File ini akan otomatis memperbaiki:
- ✅ Menambahkan column `role` jika belum ada
- ✅ Set **SEMUA user** sebagai admin (policy baru)
- ✅ Refresh session data
- ✅ Create upload directory

**B. Manual Fix via SQL**
```sql
-- 1. Add role column (default = admin)
ALTER TABLE users 
ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'admin' 
AFTER password;

-- 2. Set ALL users as admin (new policy)
UPDATE users 
SET role = 'admin' 
WHERE role = 'user';
```

**C. Atau import SQL file**
```
File: fix_database.sql
Import via phpMyAdmin atau command line
```

---

### **2. User yang ditambahkan seperti tidak ada**

#### Penyebab:
- Database tidak tersimpan dengan benar
- Email sudah terdaftar
- Error saat INSERT

#### Solusi:

**Cek database dengan debug tool:**
```
http://localhost/portofolio-rama/debug_system.php
```

Tool ini akan menampilkan:
- ✅ Semua user yang terdaftar
- ✅ Role masing-masing user
- ✅ Status session
- ✅ Error jika ada

---

### **3. Tidak bisa login setelah register**

#### ✅ BY DESIGN - Security Feature
Sistem meminta user untuk **login manual** setelah register:
- Ini untuk keamanan dan memastikan autentikasi yang proper
- Setelah register berhasil, akan muncul pesan sukses
- User diminta input email & password untuk login
- Setelah login, **semua user** otomatis punya akses admin

---

### **4. Error "Akses ditolak" di admin-blog.php**

#### Penyebab:
- Session tidak memiliki role (outdated session)
- User belum login
- Database role masih 'user' (belum update ke policy baru)

#### Solusi:

**Cek status dengan debug tool:**
```
http://localhost/portofolio-rama/debug_system.php
```

**Jika session bermasalah:**
1. Jalankan: `http://localhost/portofolio-rama/auto_fix.php` (convert semua user ke admin)
2. Logout: `http://localhost/portofolio-rama/logout.php`
3. Login kembali dengan email & password
4. ✅ Sekarang harusnya punya akses admin

---

## 📋 Checklist System Requirements

### Database
- [x] Database `portofolio_db` exists
- [x] Table `users` exists dengan columns:
  - `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
  - `fullname` (VARCHAR)
  - `email` (VARCHAR, UNIQUE)
  - `password` (VARCHAR)
  - `role` (ENUM: 'admin', 'user') DEFAULT 'admin' ⭐ PENTING
  - `created_at` (TIMESTAMP)
- [x] Table `blog_posts` exists

### Files
- [x] config.php - Database connection
- [x] login.php - Login & Registration
- [x] logout.php - Logout functionality
- [x] admin-blog.php - Admin panel
- [x] save_post.php - Save blog posts
- [x] uploads/ directory - Image storage (auto-created)

### Permissions
- [x] PHP has write access to create uploads/ directory
- [x] uploads/ directory is writable (chmod 777)

---

## 🎯 Cara Penggunaan yang Benar

### 🆕 KEBIJAKAN BARU: Semua User = Admin

**PERUBAHAN PENTING:**
- ✅ **SEMUA pengguna yang register otomatis menjadi ADMIN**
- ✅ Tidak ada lagi pembedaan user pertama/kedua
- ✅ Setiap user punya akses penuh ke blog management

### Untuk Semua User (Admin)

1. **Register akun baru**
   - Buka: `http://localhost/portofolio-rama/login.php`
   - Klik tab "Daftar"
   - Isi form registrasi:
     - Nama Lengkap
     - Email
     - Password
     - Konfirmasi Password
   - Klik "DAFTAR"
   - ✅ Otomatis terdaftar sebagai ADMIN
   - ✅ Akan diminta login untuk melanjutkan

2. **Login**
   - Masukkan email dan password
   - Klik "LOGIN"
   - ✅ Otomatis redirect ke admin panel

3. **Mengelola Blog**
   - Di `admin-blog.php`, isi form:
     - Judul Artikel
     - Ringkasan
     - Konten
     - Upload Gambar (opsional)
   - Klik "Simpan Artikel"
   - ✅ Artikel tersimpan

4. **Logout**
   - Klik "Logout" di navbar atau
   - Buka: `http://localhost/portofolio-rama/logout.php`

---

## 🛠️ Debug Tools yang Tersedia

### 1. debug_system.php
**Fungsi:** Diagnostic tool lengkap
**URL:** `http://localhost/portofolio-rama/debug_system.php`

**Menampilkan:**
- ✅ Status database connection
- ✅ Struktur tabel users
- ✅ List semua user beserta role
- ✅ Status session
- ✅ List blog posts
- ✅ File permissions
- ✅ Recommendations untuk fix

### 2. auto_fix.php
**Fungsi:** Auto-repair common issues
**URL:** `http://localhost/portofolio-rama/auto_fix.php`

**Memperbaiki:**
- ✅ Menambahkan column role
- ✅ Set admin pertama
- ✅ Refresh session
- ✅ Create uploads directory
- ✅ Verify database tables

### 3. simple_login_test.php
**Fungsi:** Test login tanpa styling
**URL:** `http://localhost/portofolio-rama/simple_login_test.php`

### 4. test_form_submit.php
**Fungsi:** Test form submission blog
**URL:** `http://localhost/portofolio-rama/test_form_submit.php`

---

## 📝 Error Messages & Solusi

| Error Message | Penyebab | Solusi |
|---------------|----------|--------|
| "Akses ditolak" | Bukan admin | Check role di debug_system.php |
| "Email sudah terdaftar" | Email duplikat | Gunakan email lain atau login |
| "Koneksi database gagal" | Config salah | Cek config.php |
| "Column 'role' doesn't exist" | Database outdated | Run auto_fix.php |
| "Undefined index: role" | Session incomplete | Logout & login kembali |
| "Cannot insert blog post" | Bukan admin / session error | Check debug_system.php |

---

## 🔄 Langkah Reset Complete

Jika ingin reset system dari awal:

```sql
-- 1. Drop & recreate tables
DROP TABLE IF EXISTS blog_posts;
DROP TABLE IF EXISTS users;

-- 2. Import fresh database
SOURCE rama_blog.sql;
```

Kemudian:
1. Jalankan `auto_fix.php` untuk ensure role column exists
2. Register user baru (akan jadi admin otomatis)
3. Login dan mulai menambah blog posts

---

## ⚙️ System Updates (Latest)

### 🆕 Update 1: All Users Are Admin (LATEST)
- **SEMUA user yang register otomatis menjadi admin**
- Tidak ada lagi pembedaan user pertama/kedua/dst
- Setiap user punya akses penuh ke blog management
- Setelah register, user diminta login untuk keamanan

### ✅ Update 2: Security Enhancement
- User harus login manual setelah register (tidak auto-login)
- Memastikan autentikasi yang proper
- Session management lebih secure

### ✅ Update 3: Role Column Check
- System otomatis detect jika role column missing
- Auto-fix tool bisa menambahkan column otomatis
- Default role = 'admin' untuk semua user

### ✅ Update 4: Better Error Messages
- Error lebih deskriptif
- Include PDO exception messages untuk debugging

---

## 📞 Quick Commands

### Check if everything is OK:
```
http://localhost/portofolio-rama/debug_system.php
```

### Fix issues automatically:
```
http://localhost/portofolio-rama/auto_fix.php
```

### Register first admin:
```
http://localhost/portofolio-rama/login.php
(Tab: Daftar)
```

### Go to admin panel:
```
http://localhost/portofolio-rama/admin-blog.php
```

---

## ✅ Checklist Sebelum Report Bug

Sebelum melaporkan bug, pastikan:

- [ ] Sudah jalankan `debug_system.php`
- [ ] Sudah coba `auto_fix.php`
- [ ] Role column exists di database
- [ ] User sudah register dan login
- [ ] User pertama adalah admin
- [ ] Session memiliki data `role` dan `id`
- [ ] Uploads directory exists dan writable
- [ ] Tidak ada error di browser console (F12)
- [ ] XAMPP MySQL sudah running

---

## 🎉 System Status

**Current Status:** ✅ WORKING

**Last Update:** 🆕 All Users Are Admin Policy

**Major Changes:**
- ✅ Semua user yang register otomatis jadi admin
- ✅ Manual login setelah register (security enhancement)
- ✅ Default role di database = 'admin'
- ✅ Auto-fix tool untuk convert existing users

**Known Issues:** None

**Next Update:** -

---

**Need help?** Run `debug_system.php` first!
