<?php

require_once "model/QuizAnswerModel.php";
 require_once "view/helpers.php";

 class AdminQuizAnswerController{
    private $quizAnswerModel;

    public function __construct() {
        $this->quizAnswerModel = new QuizAnswerModel();
    }

    public function index() {

        $quizAnswers = $this->quizAnswerModel->getAllAnswers();

        renderViewAdmin("view/admin/quizAnswers/index.php", compact('quizAnswers')) ;
    }
    public function create() {
        $errors = [];
        $questions = $this->quizAnswerModel->getAllQuestions(); // Lấy danh sách câu hỏi
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $question_id = trim($_POST['question_id'] ?? '');
            $answers = $_POST['answers'] ?? [];
            $is_corrects = $_POST['is_correct'] ?? [];
    
            if (empty($question_id)) {
                $errors['question_id'] = "Vui lòng chọn câu hỏi.";
            }
    
            if (empty($answers) || count(array_filter($answers, 'trim')) === 0) {
                $errors['answers'] = "Vui lòng nhập ít nhất một câu trả lời.";
            }
    
            if (empty($errors)) {
                foreach ($answers as $index => $answer_text) {
                    $is_correct = isset($is_corrects[$index]) ? 1 : 0;
                    $this->quizAnswerModel->addAnswer($question_id, trim($answer_text), $is_correct);
                }
                $_SESSION['success_message'] = "Thêm câu trả lời thành công!";
                header("Location:/admin/quizAnswers");

                exit();
            }
        }
    
          // Đảm bảo không bị lỗi undefined variable
            $answers = $answers ?? [];
            $question_id = $question_id ?? [];
            $questions = $questions ?? [];
            $is_corrects = $is_corrects ?? [];
        renderViewAdmin("view/admin/quizAnswers/create.php", compact('questions', 'question_id', 'answers', 'is_corrects', 'errors'), "Thêm câu trả lời");
    }

    public function delete($id) {
        
        if ($this->quizAnswerModel->deleteAnswer($id)) {
            $_SESSION['success_message'] = "Xóa câu trả lời thành công!";
        } else {
            $_SESSION['error_message'] = "Không thể xóa câu trả lời!";
        }
        header("Location: /admin/quizAnswers");
        exit();
    }
  // Controller: Update answer


  public function update($id) {
    $errors = [];
    $question_id = '';
    $answers = [];
    $is_corrects = [];

    // Lấy câu trả lời hiện tại cần sửa
    $quizAnswer = $this->quizAnswerModel->getAnswerById($id);

    // Kiểm tra nếu câu trả lời không tồn tại
    if (!$quizAnswer) {
        $_SESSION['error_message'] = "Câu trả lời không tồn tại.";
        header("Location: /admin/quizAnswers");
        exit();
    }

    // Lấy tất cả các câu hỏi
    $questions = $this->quizAnswerModel->getAllQuestions();
    
    // Nếu phương thức là POST (người dùng submit form)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $question_id = trim($_POST['question_id'] ?? '');
        $answers = $_POST['answers'] ?? [];
        $is_corrects = $_POST['is_correct'] ?? [];

        // Kiểm tra lỗi
        if (empty($question_id)) {
            $errors['question_id'] = "Vui lòng chọn câu hỏi.";
        }

        if (empty($answers) || count(array_filter($answers, 'trim')) === 0) {
            $errors['answers'] = "Vui lòng nhập ít nhất một câu trả lời.";
        }

        // Nếu không có lỗi, tiến hành cập nhật
        if (empty($errors)) {
            // Cập nhật câu trả lời
            foreach ($answers as $index => $answer_text) {
                $is_correct = isset($is_corrects[$index]) ? 1 : 0;
                $this->quizAnswerModel->updateAnswer($id, $question_id, trim($answer_text), $is_correct);
            }

            $_SESSION['success_message'] = "Cập nhật câu trả lời thành công!";
            header("Location: /admin/quizAnswers");
            exit();
        }
    } else {
        // Nếu phương thức là GET, điền các giá trị hiện tại vào form
        $question_id = $quizAnswer['question_id'];
        $answers[] = $quizAnswer['answer']; // Giả sử mỗi câu trả lời có 1 câu trả lời, bạn có thể thay đổi theo cách khác nếu cần
        $is_corrects[] = $quizAnswer['is_correct'];
    }

    // Truyền dữ liệu ra view
    renderViewAdmin("view/admin/quizAnswers/edit.php", compact('questions', 'question_id', 'answers', 'is_corrects', 'errors'), "Sửa câu trả lời");
}

    
    
    }


 


?>