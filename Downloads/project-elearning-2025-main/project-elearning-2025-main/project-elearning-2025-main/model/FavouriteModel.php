<?php
require_once "database.php"; // File chứa kết nối $conn

class FavouriteModel {
    public $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addFavourite($userId, $courseId) {
        $query = "INSERT INTO favourites (user_id, course_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $courseId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function removeFavourite($userId, $courseId) {
        $query = "DELETE FROM favourites WHERE user_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $courseId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function isFavourite($userId, $courseId) {
        $query = "SELECT COUNT(*) FROM favourites WHERE user_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // Trả về true nếu có yêu thích
    }
    
    public function getFavouritesByUserId($userId) {
        $query = "SELECT c.* FROM courses c 
                  JOIN favourites f ON c.id = f.course_id 
                  WHERE f.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
?>  
