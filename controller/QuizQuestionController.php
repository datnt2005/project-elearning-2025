<?php

 require_once "model/QuizQuestionModel.php";
 require_once "model/SectionModel.php";
 require_once "model/CourseModel.php";
 require_once "model/QuizModel.php";
 require_once "view/helpers.php";
 require_once "model/QuizAnswerModel.php";

 use PhpOffice\PhpSpreadsheet\IOFactory;  

 class AdminQuiQuestionController{
     private $quizQuestionModel;
     private $sectionModel;
     private $courseModel;
     private $quizModel;
     private $quizAnswerModel;

     public function __construct(){
        $this->quizQuestionModel = new QuizQuestionModel();
        $this->sectionModel = new Section();
        $this->courseModel = new Course();
        $this->quizModel = new QuizModel();
        $this->quizAnswerModel = new QuizAnswerModel();
     }

     public function index(){
        $quizQuestions = $this->quizQuestionModel->getAllQuestions();
        renderViewAdmin("view/admin/quizzeQuestions/index.php", compact('quizQuestions'), "quizQuestion List");  
     }

     //Thêm API lấy Sections theo Course
     public function getSections() {
        header('Content-Type: application/json');
    
        $sections = $this->sectionModel->getSectionsByCourse($_GET['course_id']);

        
        echo json_encode(['status' => 'success', 'data' => $sections]);
        exit();
    }
    

    // Thêm API lấy Quizzes theo Section
    public function getQuizzes() {
        header('Content-Type: application/json');
    
        $quizzes = $this->quizModel->getQuizzesBySectionId($_GET['section_id']);

        
        echo json_encode(['status' => 'success', 'data' => $quizzes]);
        exit();
    }
    
    
    
    

    public function create() {
        $errors = [];
        // Lấy danh sách khóa học
        $courses = $this->courseModel->getAllCourses();

        // Lấy giá trị từ form
        $course_id = $_POST['course_id'] ?? '';
        $section_id = $_POST['section_id'] ?? '';
        $quiz_id = $question = $type = "";
        $quizs = [];
        $sections = [];

        // Nếu có course_id, lấy danh sách sections theo course
        if (!empty($course_id)) {
            $sections = $this->sectionModel->getSectionsByCourse($course_id);
        }

        // Nếu có section_id, lấy danh sách quizs theo section
        if (!empty($section_id)) {
            $quizs = $this->quizQuestionModel->getQuizzesBySection($section_id);
        }

        // Lấy danh sách kiểu câu hỏi từ ENUM
        $types = $this->quizQuestionModel->getQuestionTypes();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_question'])) {
            $quiz_id = trim($_POST['quiz_id'] ?? '');
            $question = trim($_POST['question'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $section_id = trim($_POST['section_id'] ?? '');
            $course_id = trim($_POST['course_id'] ?? '');

            // Kiểm tra dữ liệu đầu vào
            if (empty($course_id)) $errors['course_id'] = "Vui lòng chọn Khóa học";
            if (empty($section_id)) $errors['section_id'] = "Vui lòng chọn Section";
            if (empty($quiz_id)) $errors['quiz_id'] = "Vui lòng chọn bài kiểm tra";
            if (empty($type)) $errors['type'] = "Vui lòng chọn loại câu hỏi";

            // Xử lý upload file
            $file_name = $file_path = "";
            if (!empty($_FILES['file']['name'])) { // Kiểm tra nếu có file được chọn
                if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/files/';
                    $file_name = basename($_FILES['file']['name']);
                    $file_path = $upload_dir . $file_name;

                    // Kiểm tra thư mục đã tồn tại chưa
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true); // Tạo thư mục nếu chưa có
                    }

                    // Di chuyển file tải lên vào thư mục
                    if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                        $errors['file'] = "Lỗi khi tải file lên.";
                    }
                } else {
                    $errors['file'] = "Có lỗi xảy ra khi tải file.";
                }
            }

            if (empty($errors)) {
                // Thêm câu hỏi vào database
                if ($this->quizQuestionModel->addQuestion($quiz_id, $question, $type, $section_id, $course_id)) {
                    // Nếu có file Excel, đọc và thêm dữ liệu vào database
                    if (!empty($file_path)) {
                        try {
                            $spreadsheet = IOFactory::load($file_path); // Đọc file Excel
                            $worksheet = $spreadsheet->getActiveSheet();
                            $rows = $worksheet->toArray(); // Chuyển worksheet thành mảng
                        } catch (Exception $e) {
                            $errors['file'] = "Lỗi khi đọc file Excel: " . $e->getMessage();
                        }

                        if (empty($errors)) {
                            // Duyệt qua các hàng trong file Excel
                            foreach ($rows as $index => $row) {
                                if ($index === 0) continue; // Bỏ qua dòng tiêu đề

                           
                                // Giả sử cấu trúc file có cột câu hỏi, loại câu hỏi, các đáp án, đáp án đúng
                                [$question_text, $question_type, $answer_a, $answer_b, $answer_c, $answer_d, $correct_answer] = $row;

                                // Thêm câu hỏi vào cơ sở dữ liệu
                                $question_id = $this->quizQuestionModel->addQuestion($quiz_id, $question_text, $question_type);

                                // Nếu là trắc nghiệm, thêm các đáp án
                                if ($question_type === 'multiple_choice') {
                                    $answers = [$answer_a, $answer_b, $answer_c, $answer_d];
                                    $correct_index = ord(strtolower($correct_answer)) - 97; // A=0, B=1, C=2, D=3

                                    foreach ($answers as $index => $answer) {
                                        $answer = trim($answer); // Xóa khoảng trắng

                                        if (empty($answer)) {
                                            die("Lỗi: Đáp án rỗng!");
                                        }

                                        $is_correct = ($index === $correct_index) ? 1 : 0;
                                        $this->quizAnswerModel->addAnswer($question_id, $answer, $is_correct);
                                    }
                                }
                            }
                        }

                        // Lưu thông tin file vào bảng uploaded_files nếu không có lỗi
                        if (empty($errors)) {
                            $fileModel = $this->quizQuestionModel->saveFile($file_name, $file_path, pathinfo($file_name, PATHINFO_EXTENSION), $quiz_id);
                            if (!$fileModel) {
                                $errors['file'] = "Lỗi khi lưu thông tin file vào database.";
                            }
                        }
                    }

                    // Kiểm tra xem có lỗi không và thông báo thành công
                    if (empty($errors)) {
                        $_SESSION['success_message'] = "Thêm câu hỏi thành công!";
                        header("Location: /admin/quizQuests");
                        exit();
                    }
                } else {
                    $errors['database'] = "Không thể thêm câu hỏi vào database.";
                }
            }
        }

        // Hiển thị lỗi nếu có
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
        }

        renderViewAdmin("view/admin/quizzeQuestions/create.php", compact('courses', 'sections', 'quizs', 'types', 'course_id', 'section_id', 'quiz_id', 'question', 'type', 'errors'), "Thêm câu hỏi");
    }
    
    
    public function update($id){
        $errors = [];
     
         // Lấy dữ liệu câu hỏi hiện tại
         $quizQuestion = $this->quizQuestionModel->getQuestionById($id);
        //  var_dump($quizQuestion);
      

     
         if (!$quizQuestion) {
             $_SESSION['error_message'] = "Câu hỏi không tồn tại!";
             header("Location: /admin/quizzeQuestions");
             exit();
         }
     
         // Lấy danh sách tất cả khóa học
         $courses = $this->courseModel->getAllCourses();
        
       // Lấy section_id từ quiz_id
        $quizInfo = $this->quizQuestionModel->getSectionIdByQuizId($quizQuestion['quiz_id']);
        // var_dump($quizInfo);
        // exit();

        $section_id = $quizInfo['section_id'] ?? null;
     

        // Lấy course_id từ section_id
        $course_id = null;
        if ($section_id) {
            $sectionInfo = $this->quizQuestionModel->getCourseIdBySectionId($section_id);
            $course_id = $sectionInfo['course_id'] ?? null;
            // var_dump($course_id);
            // die();
            
        }

          
     
                 // Lấy danh sách sections theo course_id
         $sections = !empty($course_id) ? $this->sectionModel->getSectionsByCourse($course_id) : [];
        //  var_dump($sections);
        //  exit();
        
         // Lấy danh sách bài kiểm tra theo section_id
         $quizs = !empty($section_id) ? $this->quizQuestionModel->getQuizzesBySection($section_id) : [];
        //  var_dump($quizs);
        //  exit();
     
         // Lấy danh sách kiểu câu hỏi
         $types = $this->quizQuestionModel->getQuestionTypes();

        $quiz_id = $quizQuestion['quiz_id'];
        $question = $quizQuestion['question'];
        $type = $quizQuestion['type'];
     
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy giá trị từ form
            $course_id = trim($_POST['course_id'] ?? '');
            $section_id = trim($_POST['section_id'] ?? '');
            $quiz_id = trim($_POST['quiz_id'] ?? '');
            $question = trim($_POST['question'] ?? '');
            $type = trim($_POST['type'] ?? '');
     
            if (empty($course_id)) $errors['course_id'] = "Vui lòng chọn khóa học";
            if (empty($section_id)) $errors['section_id'] = "Vui lòng chọn phần";
            if (empty($quiz_id)) $errors['quiz_id'] = "Vui lòng chọn bài kiểm tra";
            if (empty($question)) $errors['question'] = "Vui lòng nhập câu hỏi";
            if (empty($type)) $errors['type'] = "Vui lòng chọn loại câu hỏi";
     
            if (empty($errors)) {
                if ($this->quizQuestionModel->update($id, $quiz_id, $question, $type, $section_id, $course_id)) {
                    $_SESSION['success_message'] = "Sửa câu hỏi thành công";
                    ob_start();
                    header("Location: /admin/quizQuests");
                    exit();
                } else {
                    $_SESSION['error_message'] = "Sửa câu hỏi thất bại";
                    header("Location: /admin/quizQuests");
                    exit();
                }
            }
        }
     
        renderViewAdmin("view/admin/quizzeQuestions/edit.php", compact( 'course_id', 'section_id', 'courses', 'sections', 'quizs', 'types', 'quizQuestion', 'quiz_id', 'question', 'type', 'errors'), "Sửa câu hỏi");
    }
     

     public function delete($id){
            $this->quizQuestionModel->delete($id);
            $_SESSION['success_message'] = "Xóa bài Tâp thành công";
            header("Location:/admin/quizQuests");
            exit();
     }
 }

?>