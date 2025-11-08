<?php
session_start();
require_once 'config.php';

echo "<style>
body { font-family: Arial; padding: 20px; background: #f5f5f5; }
.section { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.warning { color: orange; font-weight: bold; }
h2 { color: #333; border-bottom: 2px solid #4A90E2; padding-bottom: 10px; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
th { background: #4A90E2; color: white; }
pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
</style>";

echo "<h1>🔍 Debug System - Login & Blog</h1>";

// ========================================
// 1. DATABASE CONNECTION TEST
// ========================================
echo "<div class='section'>";
echo "<h2>1. Database Connection</h2>";
try {
    $stmt = $pdo->query("SELECT DATABASE() as dbname");
    $db = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p class='success'>✅ Database connected: <strong>{$db['dbname']}</strong></p>";
} catch(PDOException $e) {
    echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// ========================================
// 2. CHECK USERS TABLE
// ========================================
echo "<div class='section'>";
echo "<h2>2. Users Table Check</h2>";
try {
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p class='success'>✅ Table 'users' exists</p>";
        
        // Check table structure
        $stmt = $pdo->query("DESCRIBE users");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Table Structure:</h3>";
        echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td><strong>{$col['Field']}</strong></td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>{$col['Default']}</td>";
            echo "<td>{$col['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check if role column exists
        $role_exists = false;
        foreach ($columns as $col) {
            if ($col['Field'] == 'role') {
                $role_exists = true;
                echo "<p class='success'>✅ Column 'role' exists: {$col['Type']}</p>";
                break;
            }
        }
        
        if (!$role_exists) {
            echo "<p class='error'>❌ Column 'role' NOT FOUND! This is the problem!</p>";
            echo "<p class='warning'>⚠️ You need to add the 'role' column to users table</p>";
            echo "<pre>ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER password;</pre>";
        }
        
        // Count users
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p><strong>Total Users:</strong> {$count['total']}</p>";
        
        // List all users
        if ($count['total'] > 0) {
            $stmt = $pdo->query("SELECT id, fullname, email, " . ($role_exists ? "role," : "") . " created_at FROM users ORDER BY id");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<h3>All Users:</h3>";
            echo "<table><tr><th>ID</th><th>Name</th><th>Email</th>" . ($role_exists ? "<th>Role</th>" : "") . "<th>Created</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user['id']}</td>";
                echo "<td>{$user['fullname']}</td>";
                echo "<td>{$user['email']}</td>";
                if ($role_exists) {
                    $role_class = $user['role'] === 'admin' ? 'success' : '';
                    echo "<td class='$role_class'><strong>{$user['role']}</strong></td>";
                }
                echo "<td>{$user['created_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='warning'>⚠️ No users found. Register a new user first!</p>";
        }
        
    } else {
        echo "<p class='error'>❌ Table 'users' does NOT exist!</p>";
    }
} catch(PDOException $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// ========================================
// 3. SESSION CHECK
// ========================================
echo "<div class='section'>";
echo "<h2>3. Session Status</h2>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";

if (isset($_SESSION['user'])) {
    echo "<p class='success'>✅ User is logged in</p>";
    echo "<h3>Session Data:</h3>";
    echo "<pre>";
    print_r($_SESSION['user']);
    echo "</pre>";
    
    // Check if role exists in session
    if (isset($_SESSION['user']['role'])) {
        echo "<p class='success'>✅ Role in session: <strong>{$_SESSION['user']['role']}</strong></p>";
    } else {
        echo "<p class='error'>❌ Role NOT found in session!</p>";
    }
    
    // Check if id exists
    if (isset($_SESSION['user']['id'])) {
        echo "<p class='success'>✅ User ID in session: <strong>{$_SESSION['user']['id']}</strong></p>";
    } else {
        echo "<p class='error'>❌ User ID NOT found in session!</p>";
    }
} else {
    echo "<p class='warning'>⚠️ No user logged in</p>";
}
echo "</div>";

// ========================================
// 4. BLOG_POSTS TABLE CHECK
// ========================================
echo "<div class='section'>";
echo "<h2>4. Blog Posts Table Check</h2>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'blog_posts'");
    if ($stmt->rowCount() > 0) {
        echo "<p class='success'>✅ Table 'blog_posts' exists</p>";
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM blog_posts");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p><strong>Total Posts:</strong> {$count['total']}</p>";
        
        if ($count['total'] > 0) {
            $stmt = $pdo->query("SELECT id, title, user_id, created_at FROM blog_posts ORDER BY created_at DESC LIMIT 5");
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<h3>Recent Posts:</h3>";
            echo "<table><tr><th>ID</th><th>Title</th><th>User ID</th><th>Created</th></tr>";
            foreach ($posts as $post) {
                echo "<tr>";
                echo "<td>{$post['id']}</td>";
                echo "<td>{$post['title']}</td>";
                echo "<td>{$post['user_id']}</td>";
                echo "<td>{$post['created_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p class='error'>❌ Table 'blog_posts' does NOT exist!</p>";
    }
} catch(PDOException $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// ========================================
// 5. FILE PERMISSIONS CHECK
// ========================================
echo "<div class='section'>";
echo "<h2>5. File & Directory Permissions</h2>";

$files_to_check = [
    'config.php' => 'config.php',
    'login.php' => 'login.php',
    'save_post.php' => 'save_post.php',
    'admin-blog.php' => 'admin-blog.php',
    'uploads/' => 'uploads/'
];

foreach ($files_to_check as $name => $path) {
    if (file_exists($path)) {
        $perms = fileperms($path);
        $perms_str = substr(sprintf('%o', $perms), -4);
        $is_writable = is_writable($path);
        $status = $is_writable ? 'success' : 'warning';
        echo "<p class='$status'>";
        echo $is_writable ? '✅' : '⚠️';
        echo " <strong>$name:</strong> Permissions: $perms_str";
        echo $is_writable ? ' (Writable)' : ' (Not writable)';
        echo "</p>";
    } else {
        if ($name == 'uploads/') {
            echo "<p class='warning'>⚠️ <strong>$name:</strong> Directory does not exist (will be created on first upload)</p>";
        } else {
            echo "<p class='error'>❌ <strong>$name:</strong> File not found</p>";
        }
    }
}
echo "</div>";

// ========================================
// 6. RECOMMENDATIONS
// ========================================
echo "<div class='section'>";
echo "<h2>6. 🔧 Recommendations & Fixes</h2>";

$issues = [];
$fixes = [];

// Check for role column
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $role_exists = false;
    foreach ($columns as $col) {
        if ($col['Field'] == 'role') $role_exists = true;
    }
    
    if (!$role_exists) {
        $issues[] = "❌ Column 'role' missing in users table";
        $fixes[] = "<strong>Fix 1:</strong> Add role column<br><code>ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER password;</code>";
    }
} catch(PDOException $e) {}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    $issues[] = "⚠️ No user is currently logged in";
    $fixes[] = "<strong>Fix 2:</strong> <a href='login.php'>Go to Login Page</a>";
} else {
    if (!isset($_SESSION['user']['role'])) {
        $issues[] = "❌ Session missing 'role' data";
        $fixes[] = "<strong>Fix 3:</strong> Logout and login again to refresh session";
    }
    if (!isset($_SESSION['user']['id'])) {
        $issues[] = "❌ Session missing 'id' data";
        $fixes[] = "<strong>Fix 4:</strong> Logout and login again to refresh session";
    }
}

if (count($issues) > 0) {
    echo "<h3>Issues Found:</h3>";
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li>$issue</li>";
    }
    echo "</ul>";
    
    echo "<h3>How to Fix:</h3>";
    echo "<ol>";
    foreach ($fixes as $fix) {
        echo "<li>$fix</li>";
    }
    echo "</ol>";
} else {
    echo "<p class='success'>✅ All checks passed! System is working correctly.</p>";
}

echo "</div>";

// ========================================
// 7. QUICK ACTIONS
// ========================================
echo "<div class='section'>";
echo "<h2>7. ⚡ Quick Actions</h2>";
echo "<p><a href='login.php' style='padding: 10px 20px; background: #4A90E2; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>Login Page</a></p>";
echo "<p><a href='logout.php' style='padding: 10px 20px; background: #e53e3e; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>Logout</a></p>";
echo "<p><a href='admin-blog.php' style='padding: 10px 20px; background: #38a169; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>Admin Blog</a></p>";
echo "<p><a href='index.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px;'>Home Page</a></p>";
echo "</div>";

?>
