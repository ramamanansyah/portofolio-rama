<?php
session_start();
require_once 'config.php';

echo "<style>
body { font-family: Arial; padding: 20px; background: #f5f5f5; }
.section { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
.success { color: green; }
.error { color: red; }
.warning { color: orange; }
h2 { color: #333; border-bottom: 2px solid #4A90E2; padding-bottom: 10px; }
.btn { padding: 10px 20px; background: #4A90E2; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; border: none; cursor: pointer; }
</style>";

echo "<h1>🔧 Auto Fix System</h1>";

$fixes_applied = [];
$errors = [];

// ========================================
// FIX 1: ADD ROLE COLUMN IF MISSING
// ========================================
echo "<div class='section'>";
echo "<h2>Fix 1: Check Role Column</h2>";
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $role_exists = false;
    
    foreach ($columns as $col) {
        if ($col['Field'] == 'role') {
            $role_exists = true;
            break;
        }
    }
    
    if (!$role_exists) {
        echo "<p class='warning'>⚠️ Role column is missing. Adding it now...</p>";
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER password");
            $fixes_applied[] = "✅ Added 'role' column to users table";
            echo "<p class='success'>✅ Role column added successfully!</p>";
            
            // Set ALL users as admin (new policy)
            $stmt = $pdo->query("SELECT COUNT(*) FROM users");
            if ($stmt->fetchColumn() > 0) {
                $pdo->exec("UPDATE users SET role = 'admin' WHERE role = 'user'");
                $fixes_applied[] = "✅ Set all users as admin";
                echo "<p class='success'>✅ All users set as admin!</p>";
            }
        } catch(PDOException $e) {
            $errors[] = "Error adding role column: " . $e->getMessage();
            echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='success'>✅ Role column already exists</p>";
        
        // Update all existing users to admin (new policy)
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
            $user_count = $stmt->fetchColumn();
            
            if ($user_count > 0) {
                echo "<p class='warning'>⚠️ Found {$user_count} user(s) with 'user' role. Converting to admin...</p>";
                $pdo->exec("UPDATE users SET role = 'admin' WHERE role = 'user'");
                $fixes_applied[] = "✅ Converted {$user_count} user(s) to admin";
                echo "<p class='success'>✅ All users are now admin!</p>";
            } else {
                echo "<p class='success'>✅ All existing users are already admin</p>";
            }
        } catch(PDOException $e) {
            echo "<p class='error'>❌ Error updating users: " . $e->getMessage() . "</p>";
        }
    }
} catch(PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// ========================================
// FIX 2: CHECK AND FIX USER SESSIONS
// ========================================
echo "<div class='section'>";
echo "<h2>Fix 2: Check Session Data</h2>";
if (isset($_SESSION['user'])) {
    echo "<p class='success'>✅ User is logged in</p>";
    
    // Check if session has all required fields
    $needs_refresh = false;
    
    if (!isset($_SESSION['user']['role']) || !isset($_SESSION['user']['id'])) {
        $needs_refresh = true;
        echo "<p class='warning'>⚠️ Session is incomplete. Need to refresh...</p>";
        
        // Try to reload user data from database
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$_SESSION['user']['email']]);
            $fresh_user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($fresh_user) {
                $_SESSION['user'] = $fresh_user;
                $fixes_applied[] = "✅ Refreshed session data from database";
                echo "<p class='success'>✅ Session refreshed successfully!</p>";
                echo "<pre>";
                print_r($_SESSION['user']);
                echo "</pre>";
            }
        } catch(PDOException $e) {
            $errors[] = "Could not refresh session: " . $e->getMessage();
            echo "<p class='error'>❌ Error refreshing session: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='success'>✅ Session data is complete</p>";
        echo "<p>User: {$_SESSION['user']['fullname']} ({$_SESSION['user']['role']})</p>";
    }
} else {
    echo "<p class='warning'>⚠️ No user logged in. Please login first.</p>";
}
echo "</div>";

// ========================================
// FIX 3: CREATE UPLOADS DIRECTORY
// ========================================
echo "<div class='section'>";
echo "<h2>Fix 3: Check Uploads Directory</h2>";
if (!file_exists('uploads')) {
    echo "<p class='warning'>⚠️ Uploads directory not found. Creating it...</p>";
    try {
        mkdir('uploads', 0777, true);
        $fixes_applied[] = "✅ Created uploads directory";
        echo "<p class='success'>✅ Uploads directory created!</p>";
    } catch(Exception $e) {
        $errors[] = "Could not create uploads directory: " . $e->getMessage();
        echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='success'>✅ Uploads directory exists</p>";
    $is_writable = is_writable('uploads');
    if ($is_writable) {
        echo "<p class='success'>✅ Uploads directory is writable</p>";
    } else {
        echo "<p class='warning'>⚠️ Uploads directory is not writable</p>";
        echo "<p>Please run: <code>chmod 777 uploads</code></p>";
    }
}
echo "</div>";

// ========================================
// FIX 4: VERIFY DATABASE TABLES
// ========================================
echo "<div class='section'>";
echo "<h2>Fix 4: Verify Database Tables</h2>";

$required_tables = ['users', 'blog_posts'];
foreach ($required_tables as $table) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p class='success'>✅ Table '$table' exists</p>";
        } else {
            echo "<p class='error'>❌ Table '$table' is missing!</p>";
            $errors[] = "Missing table: $table";
        }
    } catch(PDOException $e) {
        echo "<p class='error'>❌ Error checking table '$table': " . $e->getMessage() . "</p>";
    }
}
echo "</div>";

// ========================================
// SUMMARY
// ========================================
echo "<div class='section'>";
echo "<h2>📊 Summary</h2>";

if (count($fixes_applied) > 0) {
    echo "<h3 class='success'>✅ Fixes Applied:</h3>";
    echo "<ul>";
    foreach ($fixes_applied as $fix) {
        echo "<li>$fix</li>";
    }
    echo "</ul>";
}

if (count($errors) > 0) {
    echo "<h3 class='error'>❌ Errors:</h3>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

if (count($fixes_applied) == 0 && count($errors) == 0) {
    echo "<p class='success'>✅ No fixes needed! Everything is working correctly.</p>";
}

echo "</div>";

// ========================================
// NEXT STEPS
// ========================================
echo "<div class='section'>";
echo "<h2>🎯 Next Steps</h2>";

if (isset($_SESSION['user'])) {
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
        echo "<p class='success'>✅ You are logged in as ADMIN. You can now:</p>";
        echo "<ul>";
        echo "<li>✅ Add new blog posts</li>";
        echo "<li>✅ Edit existing posts</li>";
        echo "<li>✅ Delete posts</li>";
        echo "</ul>";
        echo "<p><a href='admin-blog.php' class='btn'>Go to Admin Blog</a></p>";
    } else {
        echo "<p class='warning'>⚠️ Your session might be outdated.</p>";
        echo "<p>Please logout and login again to get admin access.</p>";
        echo "<p><strong>NEW POLICY:</strong> All registered users are admin.</p>";
        echo "<p><a href='logout.php' class='btn'>Logout</a> <a href='login.php' class='btn'>Login</a></p>";
    }
} else {
    echo "<p class='warning'>⚠️ Please login or register:</p>";
    echo "<ul>";
    echo "<li>Register untuk membuat akun baru</li>";
    echo "<li><strong>SEMUA user otomatis menjadi ADMIN</strong></li>";
    echo "<li>Login setelah registrasi untuk mengelola blog</li>";
    echo "</ul>";
    echo "<p><a href='login.php' class='btn'>Go to Login Page</a></p>";
}

echo "<p><a href='debug_system.php' class='btn' style='background: #667eea;'>View Detailed Debug Info</a></p>";

echo "</div>";

?>
