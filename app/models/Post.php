<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Post {
    private $conn;
    private $table = 'posts';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT p.*, u.name as author_name 
                FROM " . $this->table . " p 
                LEFT JOIN users u ON p.author_id = u.id 
                ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT p.*, u.name as author_name 
                FROM " . $this->table . " p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (title, slug, content, category, status, author_id) 
                VALUES 
                (:title, :slug, :content, :category, :status, :author_id)";

        $stmt = $this->conn->prepare($query);

        // Create slug from title
        $data['slug'] = $this->createSlug($data['title']);

        // Bind data
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':author_id', $data['author_id']);

        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                SET title = :title, 
                    slug = :slug, 
                    content = :content, 
                    category = :category, 
                    status = :status 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Create slug from title if title is changed
        if(isset($data['title'])) {
            $data['slug'] = $this->createSlug($data['title']);
        }

        // Bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':status', $data['status']);

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function createSlug($title) {
        // Convert the title to lowercase
        $slug = strtolower($title);
        
        // Replace non-alphanumeric characters with a dash
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        
        // Remove multiple consecutive dashes
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Remove dashes from the beginning and end
        $slug = trim($slug, '-');
        
        return $slug;
    }
} 