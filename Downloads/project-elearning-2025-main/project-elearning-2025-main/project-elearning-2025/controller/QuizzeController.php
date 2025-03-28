<?php

require_once "model/QuizQuestionModel.php";
require_once "model/QuizAnswerModel.php";
require_once "model/SectionModel.php";
require_once "model/CourseModel.php";
require_once "model/QuizModel.php";
require_once "view/helpers.php";

class AdminQuizzeController {
    private $quizModel;
    private $sectionModel;
    private $CourseModel;
    
    public function __construct() {
        $this->quizModel = new QuizModel();
        $this->sectionModel = new Section();
        $this->CourseModel = new Course();
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
        
        // Lấy danh sách khóa học từ CourseModel
        $courses = $this->CourseModel->getAllCourses();
        
        // Lấy danh sách sections từ SectionModel
        $sections = $this->sectionModel->getAllSections(); 
    
        // Nhóm sections theo course_id
        $sectionsByCourse = [];
        foreach ($sections as $section) {
            $sectionsByCourse[$section['course_id']][] = $section;
        }
    
        $course_id = $section_id = $title = $description = ""; // Khởi tạo trước
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course_id = $_POST['course_id'] ?? ''; 
            $section_id = trim($_POST['section_id'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
    
            if (empty($course_id)) $errors['course_id'] = "Vui lòng chọn khoá học";
            if (empty($section_id)) $errors['section_id'] = "Vui lòng chọn phần";
            if (empty($title)) $errors['title'] = "Vui lòng nhập Tên bài giải";
            if (empty($description)) $errors['description'] = "Vui lòng nhập Mô tả bài giải";
    
            if (empty($errors)) {
                if ($this->quizModel->addQuiz($section_id, $title, $description, $course_id)) {
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
    
        // Truyền dữ liệu xuống view
        renderViewAdmin("view/admin/quizzes/create.php", compact('courses', 'sectionsByCourse', 'course_id', 'section_id', 'title', 'description', 'errors'), "Thêm bài giải");

    }
    
    // hàm chọn khoá học sẽ hiển thị các bài của khoá học 
 

    public function deleteQuizze($id) {
        $message = "";
        $this->quizModel->delete($id);
        $_SESSION['success_message'] = "Xóa bài Tâp thành công";
        header("Location: /admin/quizzes");
        exit();
    }
   
    public function updateQuizze($id) {
        $errors = [];
    
        // Lấy thông tin bài kiểm tra từ quizModel
        $quiz = $this->quizModel->getQuizById($id);
        if (!$quiz) {
            $_SESSION['error_message'] = "Bài kiểm tra không tồn tại";
            header("Location: /admin/quizzes");
            exit();
        }
      
        // Lấy danh sách khóa học từ CourseModel
        $courses = $this->CourseModel->getAllCourses();
    
        // Lấy danh sách sections từ SectionModel
        $sections = $this->sectionModel->getAllSections();
    
        // Nhóm sections theo course_id
        $sectionsByCourse = [];
        foreach ($sections as $section) {
            $sectionsByCourse[$section['course_id']][] = $section;
        }

 
    
        // Lấy section_id từ quiz, sau đó tìm course_id tương ứng từ section_id
        $section_id = $quiz['section_id'];
      
        $course_id = ''; // Khởi tạo biến course_id
        foreach ($sections as $section) {
            if ($section['id'] == $section_id) {
                $course_id = $section['course_id']; // Lấy course_id tương ứng với section_id
                break;
            }
        }
     
    
        // Lấy các giá trị từ bài kiểm tra
        $title = $quiz['title'] ?? '';
        $description = $quiz['description'] ?? '';
    
        $section_id = $quiz['section_id'] ?? '';
    
        // Xử lý khi form được submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $course_id = trim($_POST['course_id'] ?? '');
            $section_id = trim($_POST['section_id'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
    
            // Kiểm tra lỗi
            if (empty($course_id)) $errors['course_id'] = "Vui lòng chọn khóa học";
            if (empty($section_id)) $errors['section_id'] = "Vui lòng chọn phần học";
            if (empty($title)) $errors['title'] = "Vui lòng nhập tiêu đề";
            if (empty($description)) $errors['description'] = "Vui lòng nhập mô tả";
    
            // Nếu không có lỗi, tiến hành cập nhật dữ liệu
            if (empty($errors)) {
                $success = $this->quizModel->update($id, $section_id, $title, $description, $course_id);
                $_SESSION[$success ? 'success_message' : 'error_message'] = 
                    $success ? "Cập nhật bài kiểm tra thành công" : "Cập nhật bài kiểm tra thất bại";
    
                // Chuyển hướng về trang danh sách quiz
                header("Location: /admin/quizzes");
                exit();
            }
        }
    
        // Truyền dữ liệu xuống view
        renderViewAdmin("view/admin/quizzes/edit.php", compact(
            'courses', 'sectionsByCourse', 'section_id', 'title', 'description', 'course_id', 'errors'
        ), "Sửa bài kiểm tra");
    }
    
    
    
    
    
}
?>
