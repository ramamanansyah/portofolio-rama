-- Fix Database Script
-- Run this if you're having issues with login/registration

-- 1. Add role column if it doesn't exist
-- This will give error if column already exists, that's OK
ALTER TABLE users 
ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'admin' 
AFTER password;

-- 2. Set ALL users as admin (NEW POLICY: All users are admin)
UPDATE users 
SET role = 'admin' 
WHERE role = 'user';

-- 3. Verify the changes
SELECT id, fullname, email, role, created_at FROM users;

-- 4. Check blog_posts table structure
DESCRIBE blog_posts;

-- 5. Show all posts with user info
SELECT 
    bp.id,
    bp.title,
    u.fullname as author,
    u.role as author_role,
    bp.created_at
FROM blog_posts bp
LEFT JOIN users u ON bp.user_id = u.id
ORDER BY bp.created_at DESC;
