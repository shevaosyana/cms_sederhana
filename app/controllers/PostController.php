<?php
namespace App\Controllers;

use App\Models\Post;

class PostController {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    public function index() {
        $posts = $this->postModel->getAll();
        require __DIR__ . '/../views/posts.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'category' => $_POST['category'] ?? '',
                'status' => $_POST['status'] ?? 'draft',
                'author_id' => $_SESSION['user_id']
            ];

            if ($this->postModel->create($data)) {
                $_SESSION['success'] = 'Post created successfully';
                header('Location: /posts');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to create post';
            }
        }

        require __DIR__ . '/../views/posts/create.php';
    }

    public function edit($id) {
        $post = $this->postModel->getById($id);

        if (!$post) {
            $_SESSION['error'] = 'Post not found';
            header('Location: /posts');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'category' => $_POST['category'] ?? '',
                'status' => $_POST['status'] ?? 'draft'
            ];

            if ($this->postModel->update($id, $data)) {
                $_SESSION['success'] = 'Post updated successfully';
                header('Location: /posts');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to update post';
            }
        }

        require __DIR__ . '/../views/posts/edit.php';
    }

    public function delete($id) {
        if ($this->postModel->delete($id)) {
            $_SESSION['success'] = 'Post deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete post';
        }
        header('Location: /posts');
        exit;
    }
} 