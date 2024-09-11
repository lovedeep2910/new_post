<?php

// Config.php (Configuration for database)
class Config {
    private $host = 'localhost';
    private $db = 'my_blog';
    private $user = 'root';
    private $pass = 'password';
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
    }
}
