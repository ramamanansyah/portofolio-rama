<?php
session_start();
require_once 'config.php';

echo "<h2>Login Debug Information</h2>";
echo "<style>body { font-family: Arial; padding: 20px; } .success { color: green; } .error { color: red; } code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }</style>";

// Test 1: Check if form submission is working
echo "<h3>Test 1: Check Form Submission</h3>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<p class='success'>✅ Form POST is working!</p>";
    echo "<p>Received POST data:</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    if (isset($_POST['test_submit'])) {
        echo "<p class='success'>✅ Button click detected!</p>";
    }
} else {
    echo "<p>No POST data yet. Click the button below.</p>";
}

// Test 2: Database connection
echo "<h3>Test 2: Database Connection</h3>";
try {
    $stmt = $pdo->query("SELECT DATABASE()");
    $dbname = $stmt->fetchColumn();
    echo "<p class='success'>✅ Connected to database: <code>$dbname</code></p>";
} catch(PDOException $e) {
    echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 3: Check if users table exists
echo "<h3>Test 3: Users Table</h3>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p class='success'>✅ Users table exists</p>";
        
        // Count users
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        echo "<p>Total users: <strong>$count</strong></p>";
        
        if ($count == 0) {
            echo "<p class='error'>⚠️ No users in database. Please register first!</p>";
        }
    } else {
        echo "<p class='error'>❌ Users table not found!</p>";
    }
} catch(PDOException $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test 4: JavaScript Test
echo "<h3>Test 4: Button Click Test</h3>";
?>

<form method="POST" id="testForm">
    <input type="hidden" name="test_submit" value="1">
    <button type="submit" style="padding: 15px 30px; background: linear-gradient(135deg, #4A90E2, #667eea); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;">
        CLICK ME TO TEST
    </button>
</form>

<script>
document.getElementById('testForm').addEventListener('submit', function(e) {
    console.log('Form is submitting...');
});

// Test if button is clickable
document.querySelector('button').addEventListener('click', function() {
    console.log('Button clicked!');
    alert('Button is clickable! ✅');
});
</script>

<hr>
<h3>Test 5: Actual Login Test</h3>
<p>If the button above works, try this login form:</p>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "<h4>Login Attempt Debug:</h4>";
    echo "<p>Email: <code>" . htmlspecialchars($email) . "</code></p>";
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "<p class='success'>✅ User found!</p>";
            echo "<p>User details:</p>";
            echo "<ul>";
            echo "<li>ID: " . $user['id'] . "</li>";
            echo "<li>Name: " . htmlspecialchars($user['fullname']) . "</li>";
            echo "<li>Email: " . htmlspecialchars($user['email']) . "</li>";
            echo "<li>Role: " . ($user['role'] ?? 'NULL') . "</li>";
            echo "</ul>";
            
            if (password_verify($password, $user['password'])) {
                echo "<p class='success'>✅ Password is CORRECT!</p>";
                echo "<p class='success'>✅ Login should work now!</p>";
                
                $_SESSION['user'] = $user;
                echo "<p>Session set. Redirecting...</p>";
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
            } else {
                echo "<p class='error'>❌ Password is INCORRECT</p>";
            }
        } else {
            echo "<p class='error'>❌ No user found with email: " . htmlspecialchars($email) . "</p>";
        }
    } catch(PDOException $e) {
        echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
    }
}
?>

<form method="POST" style="max-width: 400px; margin-top: 20px;">
    <input type="hidden" name="login" value="1">
    <div style="margin-bottom: 15px;">
        <input type="email" name="email" placeholder="Email" required 
               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
    </div>
    <div style="margin-bottom: 15px;">
        <input type="password" name="password" placeholder="Password" required
               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
    </div>
    <button type="submit" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #4A90E2, #667eea); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;">
        TEST LOGIN
    </button>
</form>

<hr>
<p><a href="login.php">← Back to Login Page</a> | <a href="index.php">Home</a></p>

<?php
// Check console errors
echo "<h3>Browser Console Check</h3>";
echo "<p>Open your browser console (F12) and check for any JavaScript errors.</p>";
echo "<script>console.log('✅ JavaScript is working!');</script>";
?>
