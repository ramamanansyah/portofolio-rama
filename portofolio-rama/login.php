<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            
            // Redirect based on user role (check if role exists)
            if (isset($user['role']) && $user['role'] === 'admin') {
                header("Location: admin-blog.php");
            } else {
                header("Location: index.php#blog");
            }
            exit();
        } else {
            $error = "Email atau password salah";
        }
    } catch(PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = "Password tidak cocok";
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Email sudah terdaftar";
            } else {
                // SEMUA USER OTOMATIS MENJADI ADMIN
                $role = 'admin';
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$fullname, $email, $hashed_password, $role]);
                
                // Success message dan redirect ke login page
                $success = "Pendaftaran berhasil! Anda terdaftar sebagai Admin. Silakan login untuk melanjutkan.";
                // Tidak auto-login, user harus login manual untuk keamanan
            }
        } catch(PDOException $e) {
            $error = "Pendaftaran gagal: " . $e->getMessage();
        }
    }
}

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
        header("Location: admin-blog.php");
    } else {
        header("Location: index.php#blog");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Rama Manansyah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        
        .login-header {
            text-align: center;
            padding: 40px 40px 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .login-header h1 {
            font-size: 2rem;
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }
        
        .login-header p {
            color: #718096;
            font-size: 0.95rem;
        }
        
        .form-container {
            padding: 40px;
        }
        
        .form-tabs {
            display: flex;
            margin-bottom: 30px;
            background: #f7fafc;
            border-radius: 12px;
            padding: 4px;
        }
        
        .tab-btn {
            flex: 1;
            padding: 12px;
            background: transparent;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #718096;
        }
        
        .tab-btn.active {
            background: white;
            color: #4A90E2;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .form-content {
            display: none;
        }
        
        .form-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4A90E2;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }
        
        .form-control::placeholder {
            color: #a0aec0;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #718096;
        }
        
        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #4A90E2;
        }
        
        .forgot-link {
            color: #4A90E2;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4A90E2, #667eea);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(74, 144, 226, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #feb2b2;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        
        .back-home {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-home a {
            color: #4A90E2;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-home a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }
            
            .login-header,
            .form-container {
                padding: 30px 25px;
            }
            
            .login-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Panel</h1>
            <p>Kelola konten blog Anda</p>
        </div>
        
        <div class="form-container">
            <!-- Form Tabs -->
            <div class="form-tabs">
                <button type="button" class="tab-btn active">Login</button>
                <button type="button" class="tab-btn">Daftar</button>
            </div>
            
            <!-- Error/Success Messages -->
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <div id="login-form" class="form-content active">
                <form method="POST">
                    <input type="hidden" name="login" value="1">
                    
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot?</a>
                    </div>
                    
                    <button type="submit" class="btn-primary">LOGIN</button>
                </form>
            </div>
            
            <!-- Register Form -->
            <div id="register-form" class="form-content">
                <form method="POST">
                    <input type="hidden" name="register" value="1">
                    
                    <div class="form-group">
                        <input type="text" name="fullname" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi Password" required>
                    </div>
                    
                    <button type="submit" class="btn-primary">DAFTAR</button>
                </form>
            </div>
            
            <div class="back-home">
                <a href="index.php">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
    
    <script>
        function switchTab(tab, event) {
            // Prevent default action
            if (event) {
                event.preventDefault();
            }
            
            // Remove active class from all tabs and forms
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.form-content').forEach(form => form.classList.remove('active'));
            
            // Add active class to selected tab and form
            if (event && event.target) {
                event.target.classList.add('active');
            }
            document.getElementById(tab + '-form').classList.add('active');
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'all 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Add click event listeners to tab buttons
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tab = this.textContent.toLowerCase().includes('login') ? 'login' : 'register';
                    switchTab(tab, e);
                });
            });
        });
    </script>
</body>
</html>