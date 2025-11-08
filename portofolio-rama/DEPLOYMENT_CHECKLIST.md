# ✅ Deployment Checklist - Portfolio Website

## 📋 Pre-Deployment Checklist

### 1. Database Setup
- [ ] Database `portofolio_db` created
- [ ] Import `sql/rama_blog.sql`
- [ ] Run `auto_fix.php` to verify structure
- [ ] Verify tables: `users`, `blog_posts`
- [ ] Test database connection via `debug_system.php`

### 2. File Structure
- [ ] All core PHP files in root
- [ ] Documentation in `/docs`
- [ ] SQL scripts in `/sql`
- [ ] Test files in `/tests` (optional - can delete)
- [ ] Static images in root (Hero, About, Project, Login)
- [ ] Uploads directory created and writable (777)

### 3. Configuration
- [ ] config.php - Database credentials set correctly
- [ ] config.php - Verify database name
- [ ] .htaccess - Review and enable security rules
- [ ] PHP version check (7.4+)
- [ ] MySQL version check (5.7+)

### 4. Security Checks
- [ ] config.php protected (file permissions 640)
- [ ] Passwords using bcrypt hashing
- [ ] SQL injection protection (prepared statements)
- [ ] Session management working
- [ ] File upload validation in place
- [ ] XSS protection enabled
- [ ] uploads/ directory cannot execute PHP

### 5. Functionality Testing
- [ ] Homepage loads correctly
- [ ] All navigation links work
- [ ] Registration working (creates admin user)
- [ ] Login working (redirects to admin panel)
- [ ] Logout working
- [ ] Admin panel accessible to logged-in users
- [ ] Create blog post working
- [ ] Edit blog post working
- [ ] Delete blog post working
- [ ] Image upload working
- [ ] Blog listing page working
- [ ] Single blog post view working
- [ ] Related posts showing

### 6. Responsive Design
- [ ] Desktop view (1200px+)
- [ ] Tablet view (768px - 1199px)
- [ ] Mobile view (<768px)
- [ ] All images responsive
- [ ] Forms work on mobile
- [ ] Navigation responsive

### 7. Performance
- [ ] Images optimized (<500KB recommended)
- [ ] CSS/JS minified (optional)
- [ ] Browser caching enabled (.htaccess)
- [ ] GZIP compression enabled (.htaccess)
- [ ] Page load time acceptable (<3 seconds)

### 8. Browser Testing
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (if applicable)
- [ ] Mobile browsers

---

## 🚀 Deployment Steps

### Step 1: Fresh Install
```bash
1. Copy all files to web server
2. Set proper file permissions
3. Create database
4. Import sql/rama_blog.sql
5. Edit config.php with server details
6. Run auto_fix.php
7. Test basic functionality
```

### Step 2: User Setup
```bash
1. Navigate to login.php
2. Register first user (becomes admin)
3. Login with credentials
4. Verify admin panel access
5. Create test blog post
```

### Step 3: Content Population
```bash
1. Add actual blog posts
2. Upload portfolio images
3. Update about section content
4. Add project details
5. Verify all links and images
```

### Step 4: Security Hardening
```bash
1. Enable .htaccess rules
2. Set file permissions (644 for PHP, 777 for uploads)
3. Remove test files from production
4. Enable HTTPS (if available)
5. Review error_log regularly
```

---

## 🔒 Security Checklist

### Files to Protect
- [x] config.php (contains DB credentials)
- [x] .htaccess (web server config)
- [x] uploads/ (prevent PHP execution)
- [ ] Error logs (if any)

### Security Features Enabled
- [x] Password hashing (bcrypt)
- [x] Prepared statements (SQL injection prevention)
- [x] Session management
- [x] Role-based access control
- [x] Input sanitization
- [x] File upload validation
- [x] XSS headers (.htaccess)
- [x] Clickjacking protection
- [ ] HTTPS (enable in production)
- [ ] Rate limiting (optional)

### Regular Security Tasks
- [ ] Update PHP regularly
- [ ] Update dependencies
- [ ] Monitor uploads directory
- [ ] Review access logs
- [ ] Backup database weekly
- [ ] Test login security monthly

---

## 📁 File Permissions Guide

### Recommended Permissions

| File/Directory | Permission | Reason |
|---------------|-----------|--------|
| PHP files | 644 | Read/execute only |
| config.php | 640 | Extra security |
| uploads/ | 777 | Must be writable |
| CSS/JS files | 644 | Read only |
| Images | 644 | Read only |
| .htaccess | 644 | Web server config |

### Commands (Linux/Mac)
```bash
chmod 644 *.php
chmod 640 config.php
chmod 777 uploads/
chmod 644 *.css *.js
```

### Windows (XAMPP)
- Right-click folder → Properties → Security
- Ensure appropriate read/write permissions

---

## 🧪 Testing Checklist

### Functional Tests

**Homepage:**
- [ ] All sections load (Hero, About, Services, Projects, Blog, Contact)
- [ ] Smooth scroll navigation works
- [ ] Blog posts display (latest 6)
- [ ] Images load correctly
- [ ] CTA buttons work

**Login System:**
- [ ] Registration creates user
- [ ] All users get admin role
- [ ] Login redirects correctly
- [ ] Session persists
- [ ] Logout clears session
- [ ] Error messages display

**Blog System:**
- [ ] Admin panel loads
- [ ] Create post works
- [ ] Image upload works
- [ ] Edit post works
- [ ] Delete post works (with confirmation)
- [ ] Blog listing shows all posts
- [ ] Single post view works
- [ ] Related posts display

**Forms:**
- [ ] All form validations work
- [ ] Error messages clear
- [ ] Success messages display
- [ ] CSRF protection (if implemented)

### Security Tests

**Authentication:**
- [ ] Cannot access admin panel without login
- [ ] Session timeout works (if implemented)
- [ ] Password strength enforced (if implemented)
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked

**File Upload:**
- [ ] Only allowed file types accepted
- [ ] File size limits enforced
- [ ] Files saved with unique names
- [ ] PHP files cannot be uploaded to uploads/
- [ ] Image files render correctly

---

## 📊 Performance Checklist

### Optimization Tasks

**Images:**
- [ ] Compress large images (<500KB)
- [ ] Convert to WebP (modern browsers)
- [ ] Use appropriate dimensions
- [ ] Implement lazy loading (optional)

**Code:**
- [ ] Remove console.log statements
- [ ] Remove debug code
- [ ] Minify CSS/JS (optional)
- [ ] Combine CSS/JS files (optional)

**Server:**
- [ ] Enable GZIP compression
- [ ] Enable browser caching
- [ ] Use CDN for libraries (Font Awesome, fonts)
- [ ] Enable OpCache (PHP optimization)

### Performance Targets
- [ ] First Contentful Paint < 1.8s
- [ ] Time to Interactive < 3.8s
- [ ] Speed Index < 3.4s
- [ ] Total page size < 2MB

---

## 🐛 Common Issues & Solutions

### Issue: Cannot connect to database
**Solution:**
- Check MySQL is running
- Verify database name in config.php
- Check username/password
- Ensure database is created

### Issue: Session not persisting
**Solution:**
- Check session_start() is first line
- Verify PHP session directory is writable
- Clear browser cookies
- Check server session configuration

### Issue: Images not uploading
**Solution:**
- Check uploads/ directory exists
- Verify directory is writable (777)
- Check PHP upload_max_filesize
- Check post_max_size
- Verify file extension allowed

### Issue: Access denied to admin panel
**Solution:**
- Run auto_fix.php
- Check user role in database
- Logout and login again
- Clear session and cookies

---

## 📝 Documentation Checklist

### Documentation Files
- [x] README.md - Project overview
- [x] FILE_STRUCTURE.md - File organization
- [x] DEPLOYMENT_CHECKLIST.md - This file
- [x] docs/QUICK_START.md - Getting started
- [x] docs/TROUBLESHOOTING.md - Common issues
- [x] docs/NEW_POLICY.md - Admin policy
- [x] docs/IMAGE_GUIDELINES.md - Image specs
- [x] docs/CHANGELOG_v2.0.md - Version history

### Code Documentation
- [ ] PHP files have header comments (optional)
- [ ] Complex functions documented (optional)
- [ ] Database schema documented (✓ in sql files)
- [ ] API endpoints documented (if any)

---

## 🔄 Backup Checklist

### What to Backup

**Daily:**
- [ ] Database (portofolio_db)
- [ ] uploads/ directory

**Weekly:**
- [ ] All PHP files
- [ ] config.php
- [ ] .htaccess

**Monthly:**
- [ ] Complete site backup
- [ ] Documentation
- [ ] SQL scripts

### Backup Methods
```bash
# Database
mysqldump -u root -p portofolio_db > backup_$(date +%Y%m%d).sql

# Files
tar -czf backup_$(date +%Y%m%d).tar.gz /path/to/portofolio-rama

# Uploads only
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz uploads/
```

---

## ✅ Final Pre-Launch Checklist

### Critical Items
- [ ] Database backed up
- [ ] All test files removed/hidden
- [ ] config.php credentials are correct
- [ ] Error reporting turned OFF in production
- [ ] Debug tools (debug_system.php) access restricted
- [ ] .htaccess security rules enabled
- [ ] HTTPS enabled (if available)
- [ ] Contact email working
- [ ] All links tested
- [ ] Forms tested
- [ ] Mobile responsive verified

### Nice to Have
- [ ] Google Analytics added (optional)
- [ ] Favicon added
- [ ] Meta tags optimized
- [ ] Social media meta tags (optional)
- [ ] Sitemap.xml (optional)
- [ ] robots.txt (optional)

---

## 🎉 Post-Launch Checklist

### Week 1
- [ ] Monitor error logs daily
- [ ] Check analytics (if implemented)
- [ ] Test all forms
- [ ] Verify emails sending
- [ ] Check uploads directory size
- [ ] Get user feedback

### Month 1
- [ ] Backup database
- [ ] Review security logs
- [ ] Check for broken links
- [ ] Review performance
- [ ] Plan updates

### Ongoing
- [ ] Regular backups
- [ ] Update content
- [ ] Monitor security
- [ ] Performance optimization
- [ ] User experience improvements

---

## 📞 Emergency Contacts

### Issues Requiring Immediate Action
1. **Site Down:** Check Apache/MySQL, review error logs
2. **Database Error:** Restore from backup, check connections
3. **Security Breach:** Change passwords, review access logs
4. **File Corruption:** Restore from backup

### Tools for Emergency
- `debug_system.php` - System diagnostics
- `auto_fix.php` - Auto-repair
- phpMyAdmin - Database management
- Error logs - `/xampp/apache/logs/error.log`

---

## 🎯 Success Criteria

### Website is Ready When:
- ✅ All pages load without errors
- ✅ All features work as expected
- ✅ Security measures in place
- ✅ Backups configured
- ✅ Performance acceptable
- ✅ Mobile responsive
- ✅ Browser compatible
- ✅ Documentation complete

---

**Deployment Date:** __________  
**Deployed By:** __________  
**Version:** 2.0.0  
**Status:** □ Staging  □ Production
