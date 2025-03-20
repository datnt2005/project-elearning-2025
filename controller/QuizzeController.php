<?php
require_once "model/QuizModel.php";
require_once "view/helpers.php";

class AdminQuizzeController {
    private $quizModel;
    
    public function __construct() {
        $this->quizModel = new QuizModel();
    }
    
    public function index() {
        $quizzes = $this->quizModel->getAllQuizzes();
        renderViewAdmin("view/admin/quizzes/index.php", compact('quizzes'), "quizzes List");
    }
    
    public function createQuizze() {
        $errors = [];
        $lessons = $this->quizModel->getAllLessons(); // Đổi $lession thành $lessons
        $lesson_id = $title = $description = ""; // Khởi tạo trước
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lesson_id = trim($_POST['lesson_id'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
    
            if (empty($lesson_id)) $errors['lesson_id'] = "Vui lòng chọn phần";
            if (empty($title)) $errors['title'] = "Vui lòng nhập Tên bài giải";
            if (empty($description)) $errors['description'] = "Vui lòng nhập Mô tả bài giải";
    
            if (empty($errors)) {
                if ($this->quizModel->addQuiz($lesson_id, $title, $description)) {
                    $_SESSION['success_message'] = "Thêm bài giải thành công";
                    header("Location: /quizzes");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Thêm không thành công";
                    header("Location: /quizzes");
                    exit();
                }
            }
        }
    
        renderViewAdmin("view/admin/quizzes/create.php", compact('lessons', 'lesson_id', 'title', 'description', 'errors'), "Thêm bài giải");
    }

    public function deleteQuizze($id) {
        $message = "";
        $this->quizModel->delete($id);
        $_SESSION['success_message'] = "Xóa bài Tâp thành công";
        header("Location: /quizzes");
        exit();
    }

    public function updateQuizze($id) {
        $errors = [];
        $quiz = $this->quizModel->getQuizById($id);
        $lessons = $this->quizModel->getAllLessons();
        $lesson_id = $quiz['lesson_id'];
        $title = $quiz['title'];
        $description = $quiz['description'];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lesson_id = trim($_POST['lesson_id'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
    
            if (empty($lesson_id)) $errors['lesson_id'] = "Vui lòng chọn phần";
            if (empty($title)) $errors['title'] = "Vui lòng nhập Tên bài giải";
            if (empty($description)) $errors['description'] = "Vui lòng nhập Mô tả bài giải";
    
            if (empty($errors)) {
                if ($this->quizModel->update($id, $lesson_id, $title, $description)) {
                    $_SESSION['success_message'] = "Sửa bài giải thành công";
                    header("Location: /quizzes");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Sửa bài giải khong thành công";
                    header("Location: /quizzes");
                    exit();
                }
            }
        }
    
        renderViewAdmin("view/admin/quizzes/edit.php", compact('quiz', 'lessons', 'lesson_id', 'title', 'description', 'errors'), "Sửa bài giải");
    }
    
}
?>
