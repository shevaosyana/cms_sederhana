<?php
// Fungsi untuk membersihkan input
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk mendapatkan semua post
function getAllPosts($pdo) {
    $stmt = $pdo->query("SELECT posts.*, categories.name as category_name, users.username 
                         FROM posts 
                         LEFT JOIN categories ON posts.category_id = categories.id 
                         LEFT JOIN users ON posts.user_id = users.id 
                         ORDER BY posts.created_at DESC");
    return $stmt->fetchAll();
}

// Fungsi untuk mendapatkan semua kategori
function getAllCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

// Fungsi untuk mendapatkan semua user
function getAllUsers($pdo) {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY username ASC");
    return $stmt->fetchAll();
}

// Fungsi untuk mengecek apakah user adalah admin
function isAdmin($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    return $user && $user['role'] === 'admin';
}

// Fungsi untuk menampilkan pesan flash
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?> 