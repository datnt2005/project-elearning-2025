<?php
  require_once "Database.php";

  class QuizAnswerModel {
    private $conn;


    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }
 
     // lấy tât cả câu trả lời
    public function getAllAnswers() {
        $sql = "SELECT qa.id AS answer_id, qq.id AS question_id, qq.question, 
                       qa.answer, qa.is_correct
                FROM quiz_questions qq
                LEFT JOIN quiz_answers qa ON qq.id = qa.question_id
                ORDER BY qq.id, qa.id";
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
  // lấy câu trả lời bởi id
    public function getAnswerById($id) {
        $sql = "SELECT * FROM quiz_answers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một mảng duy nhất, không phải đối tượng PDOStatement
    }
    
    
   // lấy tất cả câu hỏi
    public function getAllQuestions(){
        $query = "SELECT * FROM quiz_questions";
        $stmt = $this->conn->query($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
  // lấy tất cả câu trả lời cho câu hỏi với question_id
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
    function normalizeAnswer($answer) {
      $answer = trim(mb_strtolower($answer));
      $answer = preg_replace('/\s+/', ' ', $answer); // gộp khoảng trắng
      $answer = removeVietnameseTones($answer);
      return $answer;
  }
  
  function removeVietnameseTones($str) {
      $accents = [
          'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
          'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
          'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
          'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
          'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
          'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
          'đ'=>'d',
          'À'=>'a','Á'=>'a','Ạ'=>'a','Ả'=>'a','Ã'=>'a','Â'=>'a','Ầ'=>'a','Ấ'=>'a','Ậ'=>'a','Ẩ'=>'a','Ẫ'=>'a','Ă'=>'a','Ằ'=>'a','Ắ'=>'a','Ặ'=>'a','Ẳ'=>'a','Ẵ'=>'a',
          'È'=>'e','É'=>'e','Ẹ'=>'e','Ẻ'=>'e','Ẽ'=>'e','Ê'=>'e','Ề'=>'e','Ế'=>'e','Ệ'=>'e','Ể'=>'e','Ễ'=>'e',
          'Ì'=>'i','Í'=>'i','Ị'=>'i','Ỉ'=>'i','Ĩ'=>'i',
          'Ò'=>'o','Ó'=>'o','Ọ'=>'o','Ỏ'=>'o','Õ'=>'o','Ô'=>'o','Ồ'=>'o','Ố'=>'o','Ộ'=>'o','Ổ'=>'o','Ỗ'=>'o','Ơ'=>'o','Ờ'=>'o','Ớ'=>'o','Ợ'=>'o','Ở'=>'o','Ỡ'=>'o',
          'Ù'=>'u','Ú'=>'u','Ụ'=>'u','Ủ'=>'u','Ũ'=>'u','Ư'=>'u','Ừ'=>'u','Ứ'=>'u','Ự'=>'u','Ử'=>'u','Ữ'=>'u',
          'Ỳ'=>'y','Ý'=>'y','Ỵ'=>'y','Ỷ'=>'y','Ỹ'=>'y',
          'Đ'=>'d'
      ];
      return strtr($str, $accents);
  }
  

  }

?>