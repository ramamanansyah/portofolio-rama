-- =====================================================
-- MIGRATION SCRIPT: All Users Are Admin Policy
-- Version: 2.0
-- Date: October 15, 2025
-- =====================================================

-- DESCRIPTION:
-- This script migrates the database to the new policy where
-- ALL registered users automatically get admin role.

-- IMPORTANT:
-- Backup your database before running this script!

-- =====================================================
-- STEP 1: Ensure role column exists
-- =====================================================

-- Check if role column exists, if not create it
-- Note: This will error if column already exists, that's OK
ALTER TABLE users 
ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'admin' 
AFTER password;

-- =====================================================
-- STEP 2: Convert all existing users to admin
-- =====================================================

-- Update all users with 'user' role to 'admin'
UPDATE users 
SET role = 'admin' 
WHERE role = 'user';

-- Verify the changes
SELECT 
    'Total Users' as Info, 
    COUNT(*) as Count 
FROM users
UNION ALL
SELECT 
    'Admin Users' as Info, 
    COUNT(*) as Count 
FROM users 
WHERE role = 'admin'
UNION ALL
SELECT 
    'Regular Users' as Info, 
    COUNT(*) as Count 
FROM users 
WHERE role = 'user';

-- =====================================================
-- STEP 3: Show all users with their new roles
-- =====================================================

SELECT 
    id,
    fullname,
    email,
    role,
    created_at
FROM users
ORDER BY created_at ASC;

-- =====================================================
-- STEP 4: Verify blog_posts integrity
-- =====================================================

-- Show all posts with author info
SELECT 
    bp.id,
    bp.title,
    u.fullname as author,
    u.role as author_role,
    bp.created_at
FROM blog_posts bp
LEFT JOIN users u ON bp.user_id = u.id
ORDER BY bp.created_at DESC;

-- =====================================================
-- EXPECTED RESULTS:
-- =====================================================
-- 1. All users should have role = 'admin'
-- 2. Regular Users count should be 0
-- 3. Admin Users count should equal Total Users
-- 4. All blog posts should have valid author with admin role

-- =====================================================
-- ROLLBACK (if needed):
-- =====================================================
-- If you want to rollback to the old system:
-- 
-- UPDATE users 
-- SET role = 'user' 
-- WHERE id > (SELECT MIN(id) FROM (SELECT id FROM users) AS temp);
-- 
-- This will set only the first user as admin,
-- and everyone else as regular user.

-- =====================================================
-- MIGRATION COMPLETE!
-- =====================================================
