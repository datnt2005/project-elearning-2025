<?php
require_once "Database.php";

class Subcategory {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllSubcategory() {
        $query = "SELECT subcategories.id, subcategories.name, subcategories.description, categories.name AS category_name
                  FROM subcategories
                  JOIN categories ON subcategories.category_id = categories.id
                  ORDER BY subcategories.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubcategoryById($id) {
        $query = "SELECT * FROM subcategories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($category_id, $name, $description) {
        $query = "INSERT INTO subcategories (category_id, name, description) VALUES (:category_id, :name, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function update($id,$category_id, $name, $description) {
        $query = "UPDATE subcategories SET category_id = :category_id, name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM subcategories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


}