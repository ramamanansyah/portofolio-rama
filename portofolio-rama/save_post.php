<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    die("Akses ditolak");
}

// Check if user has admin role
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    die("Akses ditolak. Hanya admin yang dapat mengelola blog.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $excerpt = $_POST['excerpt'];
    $content = $_POST['content'];
    
    // Handle file upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $target_file;
        }
    }
    
    try {
        if (empty($post_id)) {
            // Insert new post
            $stmt = $pdo->prepare("INSERT INTO blog_posts (title, excerpt, content, image, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $excerpt, $content, $image, $_SESSION['user']['id']]);
            $_SESSION['success'] = "Artikel berhasil ditambahkan";
        } else {
            // Update existing post
            if ($image) {
                $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, excerpt = ?, content = ?, image = ? WHERE id = ?");
                $stmt->execute([$title, $excerpt, $content, $image, $post_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, excerpt = ?, content = ? WHERE id = ?");
                $stmt->execute([$title, $excerpt, $content, $post_id]);
            }
            $_SESSION['success'] = "Artikel berhasil diperbarui";
        }
        
        header("Location: admin-blog.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan artikel: " . $e->getMessage();
        header("Location: admin-blog.php");
        exit();
    }
}
?>