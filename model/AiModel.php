<?php
require_once "Database.php";

class AiModel {
    private $conn;
    private $youtubeApiKey = 'AIzaSyCfzcQIK1QrvbSVtM9-5DVvdsBzECf1Xt8'; // Thay bằng API key thực tế của YouTube Data API
    private $geminiApiKey = 'AIzaSyBGuHzk9XrINm8rNOTMYnI2_NxrdlNglL4';   // Thay bằng API key thực tế của Gemini API

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Các phương thức khác giữ nguyên, chỉ sửa generateQuestionsFromAI
    public function generateQuestionsFromAI($title, $description, $videoUrl, $questionCount = 5) {
        // Giới hạn questionCount trong khoảng 5-20
        $questionCount = max(5, min(20, (int)$questionCount));
        $randomSeed = time();

        $videoId = $this->extractVideoId($videoUrl);
        $videoInfo = $this->getYouTubeVideoInfo($videoId);
        $tagsText = !empty($videoInfo['tags']) ? "Các từ khóa: " . implode(", ", $videoInfo['tags']) . ". " : "";
        $videoDescText = !empty($videoInfo['description']) ? "Mô tả video từ YouTube: " . $videoInfo['description'] . ". " : "";

        $prompt = "Dựa trên tiêu đề bài học: '$title', mô tả: '$description', và thông tin thực tế từ video YouTube với URL: $videoUrl. " .
                  "$tagsText$videoDescText" .
                  "Hãy tạo chính xác $questionCount câu hỏi trắc nghiệm độc đáo (khác với các lần trước, mã ngẫu nhiên: $randomSeed). " .
                  "Mỗi câu hỏi phải bao gồm: 1 câu hỏi chính và 4 lựa chọn trả lời (A, B, C, D), trong đó chỉ có 1 đáp án đúng. " .
                  "Định dạng mỗi câu hỏi như sau:\n" .
                  "Câu [số]: [Câu hỏi]\n" .
                  "A) [Lựa chọn 1]\n" .
                  "B) [Lựa chọn 2]\n" .
                  "C) [Lựa chọn 3]\n" .
                  "D) [Lựa chọn 4]\n" .
                  "Đáp án đúng: [A/B/C/D]\n" .
                  "Đảm bảo:\n" .
                  "- Tạo đúng $questionCount câu hỏi.\n" .
                  "- Nội dung câu hỏi phải chi tiết, phù hợp với tiêu đề, mô tả và thông tin video.\n" .
                  "- Không tạo ít hơn hoặc nhiều hơn số lượng yêu cầu.";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$this->geminiApiKey";
        
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
        
        if (empty($text)) {
            error_log("Gemini API returned empty content: " . json_encode($result));
            return [];
        }

        error_log("Raw response from Gemini API: " . $text);

        $questionBlocks = preg_split("/Câu \d+:/", $text, -1, PREG_SPLIT_NO_EMPTY);
        $questions = [];
        
        foreach ($questionBlocks as $block) {
            $block = trim($block);
            if (empty($block)) continue;
    
            $lines = array_filter(array_map('trim', explode("\n", $block)));
            $questionText = array_shift($lines);
            $options = [];
            $correctAnswer = '';
    
            foreach ($lines as $line) {
                if (preg_match("/^[A-D]\)/", $line)) {
                    $options[] = trim(substr($line, 3)); // Bỏ "A)", "B)", v.v.
                } elseif (strpos($line, "Đáp án đúng:") === 0) {
                    $correctAnswerLetter = trim(str_replace("Đáp án đúng:", "", $line));
                    $correctAnswerIndex = ord($correctAnswerLetter) - ord('A');
                    $correctAnswer = $options[$correctAnswerIndex] ?? '';
                }
            }
    
            if (count($options) === 4 && !empty($correctAnswer)) {
                $questions[] = [
                    'question_text' => $questionText,
                    'options' => $options,
                    'correct_answer' => $correctAnswer
                ];
            }
        }
        
        // Đảm bảo trả về đúng số lượng câu hỏi yêu cầu
        $questions = array_slice($questions, 0, $questionCount);
        if (count($questions) < $questionCount) {
            error_log("Generated fewer questions than requested: " . count($questions) . " < $questionCount");
        }
        
        error_log("Generated questions: " . json_encode($questions));
        return $questions;
    }

    // Phương thức lưu câu hỏi (giữ nguyên)
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
        $optionsJson = json_encode($options);
        $stmt->bindParam(':options', $optionsJson);
        $stmt->bindParam(':correct_answer', $correctAnswer);
    
        if ($stmt->execute()) {
            return true;
        } else {
            error_log('Failed to save question: ' . print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    // Các phương thức khác giữ nguyên
    public function generateVideoContent($videoUrl, $title, $description, $videoDuration = null) {
        $videoId = $this->extractVideoId($videoUrl);
        if (!$videoId) {
            error_log("Invalid YouTube URL: $videoUrl");
            return ['status' => 'error', 'message' => 'URL video không hợp lệ'];
        }

        if ($videoDuration === null) {
            $videoDuration = $this->getYouTubeVideoDuration($videoId);
            error_log("Video duration for $videoId: $videoDuration seconds");
        } else {
            error_log("Using provided video duration: $videoDuration seconds");
        }

        $captions = $this->getYouTubeCaptions($videoId);
        
        if ($captions) {
            error_log("Successfully retrieved captions for video: $videoId");
            return ['status' => 'success', 'content' => $captions];
        } else {
            error_log("No captions available, falling back to AI generation for video: $videoId");
            return $this->generateContentFromAI($title, $description, $videoId, $videoDuration);
        }
    }

    private function getYouTubeVideoDuration($videoId) {
        $url = "https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id=$videoId&key=$this->youtubeApiKey";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $duration = $data['items'][0]['contentDetails']['duration'] ?? 'PT10M30S';
        $totalSeconds = $this->parseISO8601Duration($duration);
        error_log("Fetched duration for $videoId: $totalSeconds seconds");
        return $totalSeconds;
    }

    private function parseISO8601Duration($duration) {
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $matches);
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        $seconds = isset($matches[3]) ? (int)$matches[3] : 0;
        $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
        error_log("Parsed duration: $duration -> $totalSeconds seconds");
        return $totalSeconds;
    }

    private function extractVideoId($url) {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    private function getYouTubeCaptions($videoId) {
        $url = "https://www.googleapis.com/youtube/v3/captions?videoId=$videoId&part=snippet&key=$this->youtubeApiKey";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            error_log("Failed to get captions, HTTP code: $httpCode, response: $response");
            return null;
        }

        $data = json_decode($response, true);
        if (empty($data['items'])) {
            error_log("No captions available for video: $videoId");
            return null;
        }

        $captionId = $data['items'][0]['id'];
        $captionUrl = "https://www.googleapis.com/youtube/v3/captions/$captionId?tfmt=srt&key=$this->youtubeApiKey";
        $ch = curl_init($captionUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $captionContent = curl_exec($ch);
        curl_close($ch);

        return $this->parseSRTContent($captionContent);
    }

    private function parseSRTContent($srtContent) {
        $lines = explode("\n", $srtContent);
        $content = [];
        $currentTimestamp = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^(\d{2}:\d{2}:\d{2},\d{3}) -->/', $line, $matches)) {
                $currentTimestamp = $matches[1];
            } elseif (!empty($line) && !is_numeric($line)) {
                $content[$currentTimestamp] = $line;
            }
        }
        
        return $content;
    }

    private function getYouTubeVideoInfo($videoId) {
        $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=$videoId&key=$this->youtubeApiKey";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        $item = $data['items'][0]['snippet'] ?? [];
        return [
            'tags' => $item['tags'] ?? [],
            'description' => $item['description'] ?? ''
        ];
    }

    private function generateContentFromAI($title, $description, $videoId, $videoDuration) {
        error_log("Generating AI content with duration: $videoDuration seconds");
        
        $videoInfo = $this->getYouTubeVideoInfo($videoId);
        $tagsText = !empty($videoInfo['tags']) ? "Các từ khóa: " . implode(", ", $videoInfo['tags']) . ". " : "";
        $videoDescText = !empty($videoInfo['description']) ? "Mô tả video từ YouTube: " . $videoInfo['description'] . ". " : "";
        
        $maxDuration = $videoDuration ?: 600; // Mặc định 10 phút nếu không có duration
        $maxDurationFormatted = gmdate("i:s", $maxDuration);
        $interval = $maxDuration / 12;
        $prompt = "Dựa trên tiêu đề bài học: '$title', mô tả: '$description', và thông tin thực tế từ video YouTube với ID: $videoId. " .
                  "Độ dài video là $maxDuration giây. $tagsText$videoDescText" .
                  "Hãy tạo một bản tóm tắt nội dung chi tiết cho video này, trải đều từ 00:00 đến $maxDurationFormatted. " .
                  "Trả về dưới dạng danh sách 10 đoạn văn bản kèm thời gian ước lượng theo định dạng sau:\n" .
                  "00:00 - [Mô tả chi tiết phần đầu]\n" .
                  gmdate("i:s", (int)$interval) . " - [Mô tả chi tiết phần tiếp theo]\n" .
                  "v.v. Đảm bảo:\n" .
                  "- Tạo chính xác 10 đoạn, với thời gian cách đều từ 00:00 đến $maxDurationFormatted (khoảng cách mỗi đoạn khoảng " . (int)$interval . " giây).\n" .
                  "- Nội dung phải chi tiết, mô tả rõ ràng từng phần của video, dựa sát vào thông tin thực tế từ video nếu có, và suy ra các phần hợp lý nếu thiếu dữ liệu.\n" .
                  "- Không dừng lại sớm, phải phủ toàn bộ độ dài video.";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$this->geminiApiKey";        
        $data = ["contents" => [["parts" => [["text" => $prompt]]]]];
        
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
            return ['status' => 'error', 'message' => 'Không thể tạo nội dung từ AI, lỗi kết nối'];
        }
        
        $result = json_decode($response, true);
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
        
        if (empty($text)) {
            error_log("Gemini API returned empty content for video: $videoId, response: " . json_encode($result));
            return ['status' => 'error', 'message' => 'Không có nội dung từ AI'];
        }
        
        error_log("Raw response from Gemini API: " . $text);
        
        $lines = array_filter(explode("\n", $text));
        $segments = [];
        foreach ($lines as $line) {
            if (preg_match('/^(\d{2}:\d{2})\s*-\s*(.+)$/', $line, $matches)) {
                $segments[] = trim($matches[2]);
            }
        }
        
        $content = [];
        for ($i = 0; $i < 10; $i++) {
            $timestampSeconds = (int)($interval * $i);
            $timestamp = gmdate("i:s", $timestampSeconds);
            $content[$timestamp] = isset($segments[$i]) ? $segments[$i] : "Phần tiếp theo của video (nội dung bổ sung dựa trên thông tin trước đó)";
        }
        
        error_log("Final content generated: " . json_encode($content));
        
        return ['status' => 'success', 'content' => $content];
    }

    public function saveVideoContent($lessonId, $courseId, $userId, $videoUrl, $content) {
        $query = "INSERT INTO ai_video_contents (lesson_id, course_id, user_id, video_url, content, created_at) 
                  VALUES (:lesson_id, :course_id, :user_id, :video_url, :content, NOW())";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':video_url', $videoUrl);
        $contentJson = json_encode($content);
        $stmt->bindParam(':content', $contentJson);
        
        if ($stmt->execute()) {
            error_log("Saved video content for lesson_id: $lessonId");
            return true;
        } else {
            error_log('Failed to save video content: ' . print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    public function getVideoContentByLessonId($lessonId) {
        $query = "SELECT * FROM ai_video_contents WHERE lesson_id = :lesson_id ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        
        if (!$stmt->execute()) {
            error_log('Database error: ' . print_r($stmt->errorInfo(), true));
            throw new Exception('Truy vấn cơ sở dữ liệu thất bại');
        }
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $result['content'] = json_decode($result['content'], true);
        }
        return $result;
    }

    public function getAllQuestions() {
        $query = "SELECT * FROM ai_questions";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function deleteQuestion($id) {
        $query = "DELETE FROM ai_questions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteVideoContentByLessonId($lessonId) {
        $query = "DELETE FROM ai_video_contents WHERE lesson_id = :lesson_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        return $stmt->execute();
    }
}
?>