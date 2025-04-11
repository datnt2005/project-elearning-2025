<?php
require_once "Database.php";
 class QuizQuestionModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllQuestions() {
        $query = "SELECT * FROM quiz_questions";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionById($id) {
        $query = "SELECT * FROM quiz_questions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
        public function getQuestionsByQuizId($quiz_id) {
            $query = "SELECT * FROM quiz_questions WHERE quiz_id = :quiz_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':quiz_id', $quiz_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public function getQuizzesBySection($section_id) {
            $query = "SELECT * FROM quizzes WHERE section_id = :section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':section_id', $section_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        

    public function getAllQui(){
        $query = "SELECT id, title FROM quizzes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionTypes() {
        $query = "SHOW COLUMNS FROM quiz_questions LIKE 'type'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        $enumValues = str_getcsv($matches[1], ",", "'");
    
        return $enumValues;
    }
 


     // Lấy section_id từ quiz_id
     public function getSectionIdByQuizId($quiz_id) {
        $query = "SELECT section_id FROM quizzes WHERE id = :quiz_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
     }
    
       // Lấy course_id từ section_id
      public function getCourseIdBySectionId($section_id) {
        $query = "SELECT course_id FROM sections WHERE id = :section_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
      }
    

    
   
    

      public function addQuestion($quiz_id, $question, $type) {
        $query = "INSERT INTO quiz_questions (quiz_id, question, type) VALUES (:quiz_id, :question, :type)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':type', $type);

        
        // Thực thi câu truy vấn và trả về ID của câu hỏi vừa được thêm
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    

    public function delete($id){
        $query = "DELETE FROM quiz_questions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();  
    }

    public function update($id, $quiz_id, $question, $type, $course_id, $section_id) {
        $query = "UPDATE quiz_questions SET quiz_id = :quiz_id, question =:question, type =:type WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':id', $id); // Thêm điều kiện WHERE id
        return $stmt->execute();
    }


    public function saveFile($file_name, $file_path, $file_type, $quiz_id){
        $query = "INSERT INTO uploaded_files (file_name, file_path, file_type, quiz_id) VALUES (:file_name, :file_path, :file_type, :quiz_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':file_name', $file_name);
        $stmt->bindParam(':file_path', $file_path);
        $stmt->bindParam(':file_type', $file_type);
        $stmt->bindParam(':quiz_id', $quiz_id);
        return $stmt->execute();
    }

    public function questionExists($quiz_id, $question) {
        // Truy vấn để tìm câu hỏi với quiz_id và question cụ thể
        $query = "SELECT id FROM quiz_questions WHERE quiz_id = :quiz_id AND question = :question";
        $stmt = $this->conn->prepare($query);
        
        // Liên kết các tham số
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question', $question);
        
        // Thực thi câu lệnh SQL
        $stmt->execute();
        
        // Kiểm tra nếu có câu hỏi trùng khớp
        if ($stmt->rowCount() > 0) {
            return true; // Câu hỏi đã tồn tại
        }
        
        return false; // Không có câu hỏi trùng khớp
    }

    public function getQuestionByText($question_text) {
        $query = "SELECT * FROM quiz_questions WHERE question = :question";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question', $question_text);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về dữ liệu câu hỏi nếu tìm thấy
    }
    
    
 }

?>