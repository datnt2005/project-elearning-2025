<?php
require_once "Database.php";
  
 class UploadQuizzByFile {
    public $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function createUploadFile($file_name, $file_path, $file_type, $quiz_id) {
         $query = "INSERT INTO uploaded_files (file_name, file_path, file_type, quiz_id) VALUES (:file_name, :file_path, :file_type, :quiz_id)";
         $stmt =  $this->conn->prepare($query);
         $stmt->bindParam(':file_name', $file_name);
         $stmt->bindParam(':file_path', $file_path);
         $stmt->bindParam(':file_type', $file_type);
         $stmt->bindParam(':quiz_id', $quiz_id);
         $stmt->execute();
    }

    public function getAllUploadedFiles() {
        $query = "SELECT * FROM uploaded_files";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUploadedFileById($id) {
        $query = "SELECT * FROM uploaded_files WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteUploadedFile($id) {
        $query = "DELETE FROM uploaded_files WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function addQuestion($quiz_id, $question, $type, $section_id, $course_id){
        $query = "INSERT INTO quiz_questions (quiz_id, question, type, section_id, course_id) VALUES (:quiz_id, :question, :type, :section_id, :course_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':course_id', $course_id);
        return $stmt->execute();    
    }
}