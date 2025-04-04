<?php 
require_once 'model/UploadQuizzByFile.php';
require_once 'model/QuizQuestionModel.php';
require_once 'model/QuizAnswerModel.php';
require_once 'view/helpers.php';

class UploadQuizzByFileController {
    private $uploadQuizzByFile;
    private $quizQuestionModel;
    private $quizAnswerModel;

    public function __construct() {
        $this->uploadQuizzByFile = new UploadQuizzByFile();
        $this->quizQuestionModel = new QuizQuestionModel();
        $this->quizAnswerModel = new QuizAnswerModel();
    }

    public function index(){
        $uploadedFiles = $this->uploadQuizzByFile->getAllUploadedFiles();
        renderViewAdmin("view/admin/uploadQuizzByFile/index.php", compact('uploadedFiles'));
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        $errors = [];
        $file_name = $file_path = "";

        if (!empty($_FILES['file']['name'])) { 
            if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/files/';
                $file_name = basename($_FILES['file']['name']);
                $file_path = $upload_dir . $file_name;

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true); 
                }

                if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                    $errors['file'] = "Lỗi khi tải file lên.";
                }
            } else {
                $errors['file'] = "Có lỗi xảy ra khi tải file.";
            }
        }

        if (empty($errors)) {
            if (!empty($file_path)) {
                try {
                    $spreadsheet = IOFactory::load($file_path);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $rows = $worksheet->toArray();
                } catch (Exception $e) {
                    $errors['file'] = "Lỗi khi đọc file Excel: " . $e->getMessage();
                }

                if (empty($errors)) {
                    foreach ($rows as $index => $row) {
                        if ($index === 0) continue; 

                        // Giả sử cấu trúc file có cột câu hỏi, loại câu hỏi, các đáp án, đáp án đúng
                        [$question_text, $question_type, $answer_a, $answer_b, $answer_c, $answer_d, $correct_answer] = $row;

                        // Tạo câu hỏi và lưu vào database
                        $question_id = $this->quizQuestionModel->addQuestion($question_text, $question_type); // Tạo câu hỏi

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

                    // Lưu thông tin file vào bảng uploaded_files
                    $fileModel = $this->quizQuestionModel->saveFile($file_name, $file_path, pathinfo($file_name, PATHINFO_EXTENSION));
                    if (!$fileModel) {
                        $errors['file'] = "Lỗi khi lưu thông tin file vào database.";
                    }
                }
            }

            if (empty($errors)) {
                $_SESSION['success_message'] = "Thêm câu hỏi thành công!";
                header("Location: /admin/uploadQuizzByFile");
                exit();  // Đảm bảo có exit sau khi gọi header để tránh lỗi
            } else {
                // Nếu có lỗi, hiển thị thông báo
                foreach ($errors as $error) {
                    echo "<p style='color:red;'>$error</p>";
                }
            }
            
        }
        } else {
            renderViewAdmin("view/admin/uploadQuizzByFile/create.php", []);
        }
    }


}

    