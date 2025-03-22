<?php

 require_once "model/QuizQuestionModel.php";
 require_once "view/helpers.php";

 class AdminQuiQuestionController{
     private $quizQuestionModel;

     public function __construct(){
        $this->quizQuestionModel = new QuizQuestionModel();
     }

     public function index(){
        $quizQuestions = $this->quizQuestionModel->getAllQuestions();
        renderViewAdmin("view/admin/quizzeQuestions/index.php", compact('quizQuestions'), "quizQuestion List");  
     }

     public function create() {
      $errors = []; 
      // Lấy danh sách bài kiểm tra
      $quizs = $this->quizQuestionModel->getAllQui();
      // Lấy danh sách kiểu câu hỏi từ ENUM
      $types = $this->quizQuestionModel->getQuestionTypes(); 
      $quiz_id = $question = $type = "";
  
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $quiz_id = trim($_POST['quiz_id'] ?? '');
          $question = trim($_POST['question'] ?? '');
          $type = trim($_POST['type'] ?? '');
  
          if (empty($quiz_id)) $errors['quiz_id'] = "Vui lòng chọn bài kiểm tra";
          if (empty($question)) $errors['question'] = "Vui lòng nhập câu hỏi";
          if (empty($type)) $errors['type'] = "Vui lòng chọn loại câu hỏi";
  
          if (empty($errors)) {
              if ($this->quizQuestionModel->addQuestion($quiz_id, $question, $type)) {
                  $_SESSION['success_message'] = "Thêm câu hỏi thành công!";
                  header("Location: /admin/quizQuests");
                  exit();
              } else {
                  $_SESSION['error_message'] = "Thêm câu hỏi thất bại!";
                  header("Location: /admin/quizQuests");
                  exit();
              }
          }
      }
  
      renderViewAdmin("view/admin/quizzeQuestions/create.php", compact('quizs', 'types', 'quiz_id', 'question', 'type', 'errors'), "Thêm câu hỏi");
  }
  

  public function update($id){
   $errors = [];

   $quizQuestion = $this->quizQuestionModel->getQuestionById($id);
   $types = $this->quizQuestionModel->getQuestionTypes(); 
   $quizs = $this->quizQuestionModel->getAllQui();

   $quiz_id = $quizQuestion['quiz_id'];
   $question = $quizQuestion['question'];
   $type = $quizQuestion['type'];

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $quiz_id = trim($_POST['quiz_id'] ?? '');
       $question = trim($_POST['question'] ?? '');
       $type = trim($_POST['type'] ?? '');

       if (empty($quiz_id)) $errors['quiz_id'] = "Vui lòng chọn bài kiểm tra";
       if (empty($question)) $errors['question'] = "Vui lòng nhập câu hỏi";
       if (empty($type)) $errors['type'] = "Vui lòng chọn loại câu hỏi";

       if (empty($errors)) {
           if ($this->quizQuestionModel->update($id, $quiz_id, $question, $type)) {
               $_SESSION['success_message'] = "Sửa câu hỏi thành công";
               ob_start();
               header("Location: /admin/quizzeQuestions");
               exit();
           } else {
               $_SESSION['error_message'] = "Sửa câu hỏi thất bại";
               header("Location: /admin/quizzeQuestions");
               exit();
           }
       }
   }

   renderViewAdmin("view/admin/quizzeQuestions/edit.php", compact('quizs', 'types', 'quizQuestion', 'quiz_id', 'question', 'type', 'errors'), "Sửa câu hỏi");
}


     public function delete($id){
            $this->quizQuestionModel->delete($id);
            $_SESSION['success_message'] = "Xóa bài Tâp thành công";
            header("Location: /admin/quizzeQuestions");
            exit();
     }
 }

?>