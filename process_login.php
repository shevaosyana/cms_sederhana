<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        setFlashMessage('danger', 'Username dan password harus diisi!');
        header("Location: login.php");
        exit();
    }

    try {
        // Cek user di database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Log aktivitas login
            $stmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            setFlashMessage('success', 'Login berhasil! Selamat datang ' . $user['username']);
            header("Location: index.php");
            exit();
        } else {
            // Login gagal
            setFlashMessage('danger', 'Username atau password salah!');
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Terjadi kesalahan sistem. Silakan coba lagi nanti.');
        header("Location: login.php");
        exit();
    }
} else {
    // Jika bukan method POST, redirect ke halaman login
    header("Location: login.php");
    exit();
}
?> 