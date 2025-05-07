<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                if ($remember) {
                    // Set cookie for 30 days
                    setcookie('remember_user', $user['id'], time() + (86400 * 30), '/');
                }

                header('Location: /');
                exit;
            } else {
                $_SESSION['error'] = 'Invalid email or password';
                header('Location: /login');
                exit;
            }
        }

        // Show login form
        require __DIR__ . '/../views/login.php';
    }

    public function logout() {
        session_destroy();
        setcookie('remember_user', '', time() - 3600, '/');
        header('Location: /login');
        exit;
    }

    public function checkAuth() {
        if (isset($_SESSION['user_id'])) {
            return true;
        }

        // Check remember me cookie
        if (isset($_COOKIE['remember_user'])) {
            $user = $this->userModel->getById($_COOKIE['remember_user']);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                return true;
            }
        }

        return false;
    }
} 