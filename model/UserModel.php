<?php
require_once "Database.php";

class UserModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAllUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAdmins() {
        $query = "SELECT * FROM users WHERE role = 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE users SET name = :name, phone = :phone" . 
               (isset($data['image']) ? ", image = :image" : "") . 
               " WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':phone', $data['phone'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (isset($data['image'])) {
            $stmt->bindValue(':image', $data['image'], PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':password', $newPassword, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}