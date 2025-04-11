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
        $questionCount = $input['question_count'] ?? 5; // Lấy số lượng câu hỏi từ form, mặc định là 5
        
        if (empty($lessonTitle) || empty($lessonId) || empty($courseId) || empty($userId) || empty($videoUrl)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin cần thiết để tạo câu hỏi'
            ]);
            exit;
        }
        
        // Kiểm tra questionCount nằm trong khoảng 5-20
        $questionCount = max(5, min(20, (int)$questionCount));
        error_log("Generating $questionCount questions for lesson_id: $lessonId");

        // Tính thời gian làm bài (giây)
        $timeLimit = $questionCount < 10 ? 120 : 270; // Dưới 10 câu: 2 phút, từ 10 câu: 4 phút 30 giây

        // Xóa các câu hỏi cũ liên quan đến lesson_id trước khi tạo mới
        try {
            $this->aiModel->deleteQuestionsByLessonId($lessonId);
            error_log("Deleted existing questions for lesson_id: $lessonId");
        } catch (Exception $e) {
            error_log("Error deleting existing questions: " . $e->getMessage());
        }

        // Tạo câu hỏi mới từ AI với số lượng yêu cầu
        $questions = $this->aiModel->generateQuestionsFromAI($lessonTitle, $lessonDescription, $videoUrl, $questionCount);
        
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
                    $question['question_text'],
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
        
        // Trả về phản hồi với time_limit
        echo json_encode([
            'status' => 'success',
            'questions' => array_map(function($q) {
                return [
                    'question_text' => $q['question_text'],
                    'options' => json_decode($q['options'], true),
                    'correct_answer' => $q['correct_answer']
                ];
            }, $savedQuestions),
            'time_limit' => $timeLimit, // Thêm thời gian làm bài (giây)
            'message' => "Đã tạo thành công $questionCount câu hỏi"
        ]);
        exit;
    }

    // Phương thức để lấy câu hỏi (giữ nguyên)
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
                if (strlen($correctAnswer) === 1 && preg_match('/^[A-D]$/', $correctAnswer)) {
                    $index = ord($correctAnswer) - ord('A');
                    $correctAnswer = $options[$index] ?? $correctAnswer;
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

    // Phương thức để tạo và lấy nội dung video (giữ nguyên)
    public function generateVideoContent() {
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
        $videoDuration = 120; // Giả sử video dài 2 phút, thay bằng giá trị thực nếu có
        $forceReload = $input['force_reload'] ?? false; // Thêm tham số để buộc tạo mới
        
        if (empty($videoUrl) || empty($lessonId) || empty($courseId) || empty($userId)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin cần thiết để tạo nội dung video'
            ]);
            exit;
        }
        
        // Nếu không buộc tải lại, kiểm tra nội dung cũ
        if (!$forceReload) {
            try {
                $existingContent = $this->aiModel->getVideoContentByLessonId($lessonId);
                if ($existingContent && !empty($existingContent['content'])) {
                    echo json_encode([
                        'status' => 'success',
                        'content' => $existingContent['content']
                    ]);
                    exit;
                }
            } catch (Exception $e) {
                error_log("Error retrieving existing video content: " . $e->getMessage());
            }
        }
        
        // Xóa nội dung cũ trước khi tạo mới
        try {
            $this->aiModel->deleteVideoContentByLessonId($lessonId);
            error_log("Deleted existing video content for lesson_id: $lessonId");
        } catch (Exception $e) {
            error_log("Error deleting existing video content: " . $e->getMessage());
        }
        
        $result = $this->aiModel->generateVideoContent($videoUrl, $lessonTitle, $lessonDescription, $videoDuration);
        
        if ($result['status'] === 'error' || empty($result['content'])) {
            echo json_encode([
                'status' => 'error',
                'message' => $result['message'] ?? 'Không thể tạo nội dung video hoặc nội dung rỗng'
            ]);
            exit;
        }
        
        try {
            $this->aiModel->saveVideoContent(
                $lessonId,
                $courseId,
                $userId,
                $videoUrl,
                $result['content']
            );
            error_log("Saved video content for lesson_id: $lessonId");
        } catch (Exception $e) {
            error_log("Error saving video content: " . $e->getMessage());
        }
        
        echo json_encode([
            'status' => 'success',
            'content' => $result['content']
        ]);
        exit;
    }
}
?>