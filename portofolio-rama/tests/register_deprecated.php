<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullname, $email, $password]);
        $_SESSION['success'] = 'Registration successful. Please login.';
        header('Location: index.php#blog');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = 'Email already exists.';
        } else {
            $_SESSION['error'] = 'Registration failed.';
        }
        header('Location: index.php#blog');
        exit();
    }
}
?>