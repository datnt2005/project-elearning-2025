<?php
require_once "model/AiModel.php";
require_once "view/helpers.php";

class AiController {
    private $aiModel;

    public function __construct() {
        $this->aiModel = new AiModel();
    }

    // Phương thức để tạo câu hỏi
    public function generateQuestions() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Phương thức không được hỗ trợ'
            ]);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $videoUrl = $input['video_url'] ?? '';
        $lessonTitle = $input['lesson_title'] ?? '';
        $lessonDescription = $input['lesson_description'] ?? '';
        $lessonId = $input['lesson_id'] ?? '';
        $courseId = $input['course_id'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (empty($lessonTitle) || empty($lessonId) || empty($courseId) || empty($userId)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin cần thiết để tạo câu hỏi'
            ]);
            exit;
        }
        
        // Xóa các câu hỏi cũ liên quan đến lesson_id trước khi tạo mới
        try {
            $this->aiModel->deleteQuestionsByLessonId($lessonId);
            error_log("Deleted existing questions for lesson_id: $lessonId");
        } catch (Exception $e) {
            error_log("Error deleting existing questions: " . $e->getMessage());
        }

        // Tạo câu hỏi mới từ AI
        $questions = $this->aiModel->generateQuestionsFromAI($lessonTitle, $lessonDescription);
        
        if (empty($questions)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể tạo câu hỏi, dữ liệu AI trống.'
            ]);
            exit;
        }
        
        // Lưu các câu hỏi mới
        foreach ($questions as $question) {
            try {
                error_log('Saving question: ' . print_r($question, true));
                $this->aiModel->saveQuestion(
                    $lessonId,
                    $courseId,
                    $userId,
                    $question['question'],
                    $question['options'],
                    $question['correct_answer']
                );
            } catch (Exception $e) {
                error_log('Error saving question: ' . $e->getMessage());
            }
        }
        
        // Lấy lại danh sách câu hỏi vừa lưu
        $savedQuestions = $this->aiModel->getQuestionsByLessonId($lessonId);
        error_log('Saved questions: ' . print_r($savedQuestions, true));
        
        echo json_encode([
            'status' => 'success',
            'questions' => array_map(function($q) {
                return [
                    'question_text' => $q['question_text'],
                    'options' => json_decode($q['options'], true),
                    'correct_answer' => $q['correct_answer']
                ];
            }, $savedQuestions)
        ]);
        exit;
    }

    // Phương thức để lấy câu hỏi
    public function getQuestions() {
        header('Content-Type: application/json');
    
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Phương thức không được hỗ trợ'
            ]);
            exit;
        }
    
        $lessonId = $_GET['lesson_id'] ?? '';
    
        if (empty($lessonId) || !is_numeric($lessonId)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'lesson_id không hợp lệ'
            ]);
            exit;
        }
    
        try {
            $questions = $this->aiModel->getQuestionsByLessonId($lessonId);
            $questionList = array_map(function($q) {
                $options = json_decode($q['options'], true);
                $correctAnswer = $q['correct_answer'];
                // Nếu correct_answer chỉ chứa ký tự (A, B, C, D), chuyển thành giá trị đầy đủ trong options
                if (strlen($correctAnswer) === 1 && preg_match('/^[A-D]$/', $correctAnswer)) {
                    $index = ord($correctAnswer) - ord('A');
                    $correctAnswer = $options[$index] ?? $correctAnswer; // Giữ nguyên nếu không tìm thấy
                }
                return [
                    'question_text' => $q['question_text'],
                    'options' => $options,
                    'correct_answer' => $correctAnswer
                ];
            }, $questions);

            echo json_encode([
                'status' => 'success',
                'questions' => $questionList
            ]);
        } catch (Exception $e) {
            error_log('Lỗi khi lấy câu hỏi: ' . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi tải câu hỏi từ cơ sở dữ liệu'
            ]);
        }
        exit;
    }
}
?>