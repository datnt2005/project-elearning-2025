<?php
require_once "Database.php";

class AiModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function generateQuestionsFromAI($title, $description) {
        $randomSeed = time(); // Thêm yếu tố ngẫu nhiên dựa trên thời gian
        $prompt = "Dựa trên tiêu đề bài học: '$title' và mô tả: '$description', hãy tạo 5 câu hỏi trắc nghiệm độc đáo (khác với các lần trước, mã ngẫu nhiên: $randomSeed). Mỗi câu hỏi phải bao gồm: 1 câu hỏi chính và 4 lựa chọn trả lời (A, B, C, D), trong đó chỉ có 1 đáp án đúng. Định dạng mỗi câu hỏi như sau:\n" .
                  "Câu [số]: [Câu hỏi]\n" .
                  "A) [Lựa chọn 1]\n" .
                  "B) [Lựa chọn 2]\n" .
                  "C) [Lựa chọn 3]\n" .
                  "D) [Lựa chọn 4]\n" .
                  "Đáp án đúng: [A/B/C/D]";
    
        $apiKey = 'AIzaSyBGuHzk9XrINm8rNOTMYnI2_NxrdlNglL4'; // Thay bằng API key thật
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";
        
        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];
        
        $payload = json_encode($data);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            error_log("Failed to call Gemini API, HTTP code: $httpCode, response: $response");
            return [];
        }
        
        $result = json_decode($response, true);
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
        
        // Tách các câu hỏi dựa trên "Câu [số]:"
        $questionBlocks = preg_split("/Câu \d+:/", $text, -1, PREG_SPLIT_NO_EMPTY);
        $questions = [];
        
        foreach ($questionBlocks as $block) {
            $block = trim($block);
            if (empty($block)) continue;
    
            // Tách câu hỏi và lựa chọn
            $lines = array_filter(array_map('trim', explode("\n", $block)));
            $questionText = array_shift($lines); // Lấy dòng đầu tiên làm câu hỏi
            $options = [];
            $correctAnswer = '';
    
            foreach ($lines as $line) {
                if (preg_match("/^[A-D]\)/", $line)) {
                    $options[] = $line;
                } elseif (strpos($line, "Đáp án đúng:") === 0) {
                    $correctAnswer = trim(str_replace("Đáp án đúng:", "", $line));
                }
            }
    
            if (count($options) === 4) {
                $questions[] = [
                    'question' => $questionText,
                    'options' => $options,
                    'correct_answer' => $correctAnswer
                ];
            }
        }
        
        return array_slice($questions, 0, 5); // Giới hạn 5 câu hỏi
    }
    // Lưu câu hỏi vào database (chưa cần đáp án)
    public function saveQuestion($lessonId, $courseId, $userId, $questionText, $options = [], $correctAnswer = '') {
        if ($courseId === null) {
            error_log('Course ID is null');
        }
    
        $query = "INSERT INTO ai_questions (lesson_id, course_id, user_id, question_text, options, correct_answer, created_at) 
                  VALUES (:lesson_id, :course_id, :user_id, :question_text, :options, :correct_answer, NOW())";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':question_text', $questionText);
        $optionsJson = json_encode($options); // Chuyển mảng options thành JSON
        $stmt->bindParam(':options', $optionsJson);
        $stmt->bindParam(':correct_answer', $correctAnswer);
    
        if ($stmt->execute()) {
            return true;
        } else {
            error_log('Failed to save question: ' . print_r($stmt->errorInfo(), true));
            return false;
        }
    }
    
    // Lấy tất cả câu hỏi đã tạo (nếu có database)
    public function getAllQuestions() {
        $query = "SELECT * FROM ai_questions";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy câu hỏi theo lesson_id (nếu có database)
    public function getQuestionsByLessonId($lessonId) {
        $query = "SELECT * FROM ai_questions WHERE lesson_id = :lesson_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        if (!$stmt->execute()) {
            error_log('Lỗi cơ sở dữ liệu: ' . print_r($stmt->errorInfo(), true));
            throw new Exception('Truy vấn cơ sở dữ liệu thất bại');
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteQuestionsByLessonId($lessonId) {
        $query = "DELETE FROM ai_questions WHERE lesson_id = :lesson_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        return $stmt->execute();
    }
    // Xóa câu hỏi (nếu có database)
    public function deleteQuestion($id) {
        $query = "DELETE FROM ai_questions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
