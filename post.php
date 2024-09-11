<?php
class Post {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createPost($title, $content, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)");
        return $stmt->execute(['title' => $title, 'content' => $content, 'user_id' => $userId]);
    }

    public function getPosts() {
        $stmt = $this->conn->query("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id");
        return $stmt->fetchAll();
    }

    public function getPostById($postId) {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = :postId");
        $stmt->execute(['postId' => $postId]);
        return $stmt->fetch();
    }

    public function updatePost($postId, $title, $content) {
        $stmt = $this->conn->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
        return $stmt->execute(['title' => $title, 'content' => $content, 'id' => $postId]);
    }

    public function deletePost($postId) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute(['id' => $postId]);
    }
}
