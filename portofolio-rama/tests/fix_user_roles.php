<?php
// Script to fix existing users without role column
require_once 'config.php';

try {
    // Check if role column exists, if not add it
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($stmt->rowCount() == 0) {
        // Add role column
        $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
        echo "Role column added successfully.<br>";
    }
    
    // Update users without role to 'user'
    $stmt = $pdo->exec("UPDATE users SET role = 'user' WHERE role IS NULL");
    echo "Updated $stmt users with default role.<br>";
    
    // Make first user admin if no admin exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $admin_count = $stmt->fetchColumn();
    
    if ($admin_count == 0) {
        $pdo->exec("UPDATE users SET role = 'admin' WHERE id = (SELECT id FROM (SELECT id FROM users ORDER BY id LIMIT 1) as temp)");
        echo "First user set as admin.<br>";
    }
    
    echo "Database migration completed successfully!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
