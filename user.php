<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }

    public function register($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        return $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
    }
}
