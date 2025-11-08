<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    die(json_encode(['success' => false, 'message' => 'Akses ditolak']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$post_id]);
        
        echo json_encode(['success' => true]);
        exit();
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit();
    }
}
?>