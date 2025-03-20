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
    

    public function addQuestion($quiz_id, $question, $type) {
        $query = "INSERT INTO quiz_questions(quiz_id, question, type) VALUES (:quiz_id, :question, :type)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':type', $type);
        return $stmt->execute();

    }

    public function delete($id){
        $query = "DELETE FROM quiz_questions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();  
    }

    public function update($id, $quiz_id, $question, $type){
        $query = "UPDATE quiz_questions SET quiz_id = :quiz_id, question =:question, type =:type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':type', $type);
        return $stmt->execute();
    }
 }


?>