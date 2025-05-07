<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit();
}

// Include header
include 'includes/header.php';

// Routing sederhana
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'posts':
        include 'pages/posts.php';
        break;
    case 'categories':
        include 'pages/categories.php';
        break;
    case 'users':
        include 'pages/users.php';
        break;
    default:
        include 'pages/dashboard.php';
}

// Include footer
include 'includes/footer.php';
?> 