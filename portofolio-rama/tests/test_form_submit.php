<?php
session_start();
require_once 'config.php';

// Set admin session for testing
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'fullname' => 'Test Admin',
        'email' => 'admin@test.com',
        'role' => 'admin'
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Form Submit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
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
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
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
            font-weight: bold;
        }
        button:hover {
            background: #357ABD;
        }
        .success { 
            padding: 15px; 
            background: #d4edda; 
            color: #155724; 
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error { 
            padding: 15px; 
            background: #f8d7da; 
            color: #721c24; 
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Test Form Submit Blog</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<div class="success">✅ Form berhasil di-submit!</div>';
            echo '<h3>Data yang diterima:</h3>';
            echo '<pre style="background: #f4f4f4; padding: 15px; border-radius: 5px;">';
            print_r($_POST);
            echo '</pre>';
            
            if (!empty($_FILES['image']['name'])) {
                echo '<h3>File Upload:</h3>';
                echo '<pre style="background: #f4f4f4; padding: 15px; border-radius: 5px;">';
                print_r($_FILES['image']);
                echo '</pre>';
            }
        }
        ?>
        
        <form action="save_post.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="">
            
            <div class="form-group">
                <label for="title">Judul Artikel</label>
                <input type="text" id="title" name="title" placeholder="Masukkan judul..." required>
            </div>
            
            <div class="form-group">
                <label for="excerpt">Ringkasan</label>
                <textarea id="excerpt" name="excerpt" placeholder="Ringkasan singkat..." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="content">Konten</label>
                <textarea id="content" name="content" placeholder="Konten lengkap..." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Gambar (opsional)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <button type="submit">SIMPAN ARTIKEL</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="admin-blog.php">← Kembali ke Admin Blog</a>
        </p>
    </div>
    
    <script>
        console.log('✅ Page loaded');
        
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('✅ Form is submitting...');
            console.log('Form data:', new FormData(this));
        });
        
        document.querySelector('button').addEventListener('click', function() {
            console.log('✅ Button clicked!');
        });
    </script>
</body>
</html>
