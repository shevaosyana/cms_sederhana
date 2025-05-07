<?php
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Start session
session_start();

// Initialize Auth Controller
$auth = new App\Controllers\AuthController();

// Basic routing
$request = $_SERVER['REQUEST_URI'];
$basePath = '/cms_sederhana';

// Remove base path from request
$request = str_replace($basePath, '', $request);

// Check if user is logged in
$isLoggedIn = $auth->checkAuth();

// Public routes (no authentication required)
$publicRoutes = ['/login'];

// Redirect to login if not authenticated
if (!$isLoggedIn && !in_array($request, $publicRoutes)) {
    header('Location: /login');
    exit;
}

// Redirect to dashboard if already logged in and trying to access login page
if ($isLoggedIn && $request === '/login') {
    header('Location: /');
    exit;
}

// Simple router
switch ($request) {
    case '':
    case '/':
        require __DIR__ . '/app/views/dashboard.php';
        break;

    case '/login':
        $auth->login();
        break;

    case '/logout':
        $auth->logout();
        break;

    case '/posts':
        $postController = new App\Controllers\PostController();
        $postController->index();
        break;

    case '/posts/create':
        $postController = new App\Controllers\PostController();
        $postController->create();
        break;

    case (preg_match('/^\/posts\/edit\/(\d+)$/', $request, $matches) ? true : false):
        $postController = new App\Controllers\PostController();
        $postController->edit($matches[1]);
        break;

    case (preg_match('/^\/posts\/delete\/(\d+)$/', $request, $matches) ? true : false):
        $postController = new App\Controllers\PostController();
        $postController->delete($matches[1]);
        break;

    case '/users':
        $userController = new App\Controllers\UserController();
        $userController->index();
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/app/views/404.php';
        break;
} 