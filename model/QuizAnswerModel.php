<?php
  require_once "Database.php";

  class QuizAnswerModel {
    private $conn;

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function getAllAnswers() {
        $sql = "SELECT qa.id AS answer_id, qq.id AS question_id, qq.question, 
                       qa.answer, qa.is_correct
                FROM quiz_questions qq
                LEFT JOIN quiz_answers qa ON qq.id = qa.question_id
                ORDER BY qq.id, qa.id";
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function getAnswerById($id) {
        $sql = "SELECT * FROM quiz_answers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một mảng duy nhất, không phải đối tượng PDOStatement
    }
    
    

    public function getAllQuestions(){
        $query = "SELECT * FROM quiz_questions";
        $stmt = $this->conn->query($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAnswersByQuestionId($question_id) {
        // Lấy tất cả câu trả lời cho câu hỏi với question_id
        $sql = "SELECT id, answer, is_correct FROM quiz_answers WHERE question_id = :question_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Sử dụng fetchAll để lấy mảng kết quả
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về mảng các câu trả lời
    }
    
    

   

    public function addAnswer($question_id, $answer, $is_correct){
        $sql = "INSERT INTO quiz_answers (question_id, answer, is_correct) VALUES (:question_id, :answer, :is_correct)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
        $stmt->bindParam(':is_correct', $is_correct, PDO::PARAM_BOOL);
        return $stmt->execute();
    }
    
    

    public function updateAnswer($answer_id, $question_id, $answer, $is_correct) {
        $sql = "UPDATE quiz_answers SET question_id = :question_id, answer = :answer, is_correct = :is_correct WHERE id = :answer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':answer_id', $answer_id, PDO::PARAM_INT);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
        $stmt->bindParam(':is_correct', $is_correct, PDO::PARAM_INT);
        $stmt->execute(); // Thực thi câu lệnh
        return $stmt->rowCount();
    }
    
    
    public function deleteAnswer($id){
        $sql = "DELETE FROM quiz_answers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindparam(':id', $id);
        return $stmt->execute();       
    }

  }

?>