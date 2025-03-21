<?php

require_once "model/QuizQuestionModel.php";
require_once "model/QuizAnswerModel.php";
require_once "model/SectionModel.php";
require_once "model/QuizModel.php";
require_once "view/helpers.php";

class AdminQuizzeController {
    private $quizModel;
    private $sectionModel;
    
    public function __construct() {
        $this->quizModel = new QuizModel();
        $this->sectionModel = new Section();
    }
    
    public function index() {
        $quizzes = $this->quizModel->getAllQuizzes();
        renderViewAdmin("view/admin/quizzes/index.php", compact('quizzes'), "quizzes List");
    }


    public function show($section_id) {
        $section = $this->sectionModel->getSectionById($section_id);
        $quizzes = $this->quizModel->getQuizzesBySectionId($section_id);
        $course_id = $section['course_id'] ?? '';
    
        // Lấy danh sách câu hỏi
        $questions = [];
        $quizQuestionModel = new QuizQuestionModel(); 
        $quizAnswerModel = new QuizAnswerModel();
    
        foreach ($quizzes as $quiz) {
            $quizQuestions = $quizQuestionModel->getQuestionsByQuizId($quiz['id']);
    
            // Duyệt từng câu hỏi để lấy danh sách câu trả lời
            foreach ($quizQuestions as &$question) {
                $question['answers'] = $quizAnswerModel->getAnswersByQuestionId($question['id']);
            }
    
            $questions[] = $quizQuestions;
        }
    
        renderViewUser("view/users/quiz.php", [
            "section" => $section, 
            "quizzes" => $quizzes,
            "course_id" => $course_id,
            "questions" => $questions // Truyền danh sách câu hỏi vào View
        ], "Quiz List");
    }
    
    
    public function createQuizze() {
        $errors = [];
        $sections = $this->quizModel->getAllSections(); // Đổi $lession thành $lessons
        $section_id = $title = $description = ""; // Khởi tạo trước
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section_id = trim($_POST['section_id'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
    
            if (empty($section_id)) $errors['section_id'] = "Vui lòng chọn phần";
            if (empty($title)) $errors['title'] = "Vui lòng nhập Tên bài giải";
            if (empty($description)) $errors['description'] = "Vui lòng nhập Mô tả bài giải";
    
            if (empty($errors)) {
                if ($this->quizModel->addQuiz($section_id, $title, $description)) {
                    $_SESSION['success_message'] = "Thêm bài giải thành công";
                    header("Location: /admin/quizzes");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Thêm không thành công";
                    header("Location: /admin/quizzes");
                    exit();
                }
            }
        }
    
        renderViewAdmin("view/admin/quizzes/create.php", compact('sections', 'section_id', 'title', 'description', 'errors'), "Thêm bài giải");
    }

    public function deleteQuizze($id) {
        $message = "";
        $this->quizModel->delete($id);
        $_SESSION['success_message'] = "Xóa bài Tâp thành công";
        header("Location: /admin/quizzes");
        exit();
    }

    public function updateQuizze($id) {
        $errors = [];
    
        // Lấy dữ liệu bài giải
        $quiz = $this->quizModel->getQuizById($id);
    
        $sections = $this->quizModel->getAllSections();
        $section_id = $quiz['section_id'] ?? '';  // Tránh lỗi undefined key
        $title = $quiz['title'] ?? '';
        $description = $quiz['description'] ?? '';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section_id = trim($_POST['section_id'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
    
            if (empty($section_id)) $errors['section_id'] = "Vui lòng chọn phần";
            if (empty($title)) $errors['title'] = "Vui lòng nhập Tên bài giải";
            if (empty($description)) $errors['description'] = "Vui lòng nhập Mô tả bài giải";
    
            if (empty($errors)) {
                if ($this->quizModel->update($id, $section_id, $title, $description)) {
                    $_SESSION['success_message'] = "Sửa bài giải thành công";
                    header("Location: /admin/quizzes");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Sửa bài giải không thành công";
                    header("Location: /admin/quizzes");
                    exit();
                }
            }
        }
    
        renderViewAdmin("view/admin/quizzes/edit.php", compact('quiz', 'sections', 'section_id', 'title', 'description', 'errors'), "Sửa bài giải");
    }
    
    
}
?>
