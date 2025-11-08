<?php
// File untuk menguji login dan database
require_once 'config.php';

echo "<h2>Test Database Connection</h2>";

try {
    // Test connection
    echo "✅ Database connection: OK<br>";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table: EXISTS<br>";
        
        // Check users in database
        $stmt = $pdo->query("SELECT id, fullname, email, role FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Users in database:</h3>";
        if (count($users) > 0) {
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . htmlspecialchars($user['fullname']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . ($user['role'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "❌ No users found. Please register first.<br>";
        }
        
        // Check if role column exists
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Role column: EXISTS<br>";
        } else {
            echo "❌ Role column: MISSING<br>";
            echo "<p style='color: red;'>Run this SQL to fix:</p>";
            echo "<code>ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user';</code><br>";
        }
        
    } else {
        echo "❌ Users table: NOT FOUND<br>";
        echo "<p style='color: red;'>Please run the rama_blog.sql file first!</p>";
    }
    
} catch(PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>Test Login Form</h3>";
echo "<form method='POST'>";
echo "Email: <input type='email' name='email' required><br><br>";
echo "Password: <input type='password' name='password' required><br><br>";
echo "<input type='hidden' name='test_login' value='1'>";
echo "<button type='submit'>Test Login</button>";
echo "</form>";

// Handle test login
if ($_POST['test_login'] ?? false) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    echo "<h4>Login Test Results:</h4>";
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "✅ User found: " . htmlspecialchars($user['fullname']) . "<br>";
            echo "📧 Email: " . htmlspecialchars($user['email']) . "<br>";
            echo "🔑 Role: " . ($user['role'] ?? 'NULL') . "<br>";
            
            if (password_verify($password, $user['password'])) {
                echo "✅ Password: CORRECT<br>";
                echo "<p style='color: green;'>Login should work!</p>";
            } else {
                echo "❌ Password: INCORRECT<br>";
                echo "<p style='color: red;'>Wrong password entered.</p>";
            }
        } else {
            echo "❌ User not found with email: " . htmlspecialchars($email) . "<br>";
        }
        
    } catch(PDOException $e) {
        echo "❌ Login test error: " . $e->getMessage() . "<br>";
    }
}

echo "<br><a href='login.php'>← Back to Login Page</a>";
?>
