<?php
 require_once "Database.php";

 class QuizModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy danh sách bài tập

    public function getAllQuizzes() {
        $query = "SELECT * FROM quizzes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCourses(){
        $query = "SELECT id, title FROM courses";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSections() {
        $query = "SELECT id, title FROM sections";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuizById($id) {
        $query = "SELECT * FROM quizzes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    // lấy danh sách bài học 


 public function getQuizzesBySectionId($section_id) {
    $query = "SELECT * FROM quizzes WHERE section_id = :section_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':section_id', $section_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
    
    
    // Thêm bài tập

    public function addQuiz($section_id, $title, $description){
        $query = "INSERT INTO quizzes(section_id, title, description) VALUES (:section_id, :title, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function delete($id){
        $query = "DELETE FROM quizzes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();        
    }

    public function update($id, $section_id, $title, $description){
        $query = "UPDATE quizzes SET section_id = :section_id, title = :title, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }
 }
?>