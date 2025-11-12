<?php
namespace App;

class User {
    private $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
        $this->createTable();
    }
    
    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            bg_color VARCHAR(7) DEFAULT '#ffffff',
            text_color VARCHAR(7) DEFAULT '#000000',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->getPdo()->exec($sql);
    }
    
    public function register($username, $password, $bgColor = '#ffffff', $textColor = '#000000') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password, bg_color, text_color) 
                VALUES (:username, :password, :bg_color, :text_color)";
        $stmt = $this->db->getPdo()->prepare($sql);
        
        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':bg_color' => $bgColor,
            ':text_color' => $textColor
        ]);
    }
    
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->getPdo()->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->getPdo()->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function updateSettings($userId, $bgColor, $textColor) {
        $sql = "UPDATE users SET bg_color = :bg_color, text_color = :text_color WHERE id = :id";
        $stmt = $this->db->getPdo()->prepare($sql);
        return $stmt->execute([
            ':bg_color' => $bgColor,
            ':text_color' => $textColor,
            ':id' => $userId
        ]);
    }
}
?>