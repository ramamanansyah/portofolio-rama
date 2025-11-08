<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Login Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4A90E2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #357ABD;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Simple Login Test</h2>
        
        <?php
        session_start();
        require_once 'config.php';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    echo '<div class="message success">✅ Login berhasil! Redirecting...</div>';
                    echo '<script>setTimeout(function(){ window.location.href = "index.php"; }, 2000);</script>';
                } else {
                    echo '<div class="message error">❌ Email atau password salah!</div>';
                }
            } catch(PDOException $e) {
                echo '<div class="message error">❌ Error: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
        
        <form method="POST" onsubmit="console.log('Form submitting...'); return true;">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">LOGIN SEKARANG</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="login.php">← Kembali ke halaman login utama</a>
        </p>
    </div>
    
    <script>
        console.log('✅ Page loaded successfully');
        document.querySelector('button').addEventListener('click', function() {
            console.log('✅ Button clicked!');
        });
    </script>
</body>
</html>
