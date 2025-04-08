<?php
session_start(); // Khởi tạo session
require_once "C:/xampp/htdocs/project-elearning-2025/Database.php";

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Bạn chưa đăng nhập']);
    exit;
}

// Lấy dữ liệu từ request (giả sử là JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra nếu dữ liệu không hợp lệ
if (!$data) {
    echo json_encode(['error' => 'Dữ liệu không hợp lệ']);
    exit;
}

// Tạo đối tượng Database và lấy kết nối
$database = new Database();
$pdo = $database->getConnection();

// Bắt đầu giao dịch
$pdo->beginTransaction();

try {
    // Câu truy vấn INSERT vào bảng quiz_results
    $stmt = $pdo->prepare("INSERT INTO quiz_results (user_id, section_id, correct_count, total_questions, created_at)
                           VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([
        $_SESSION['user_id'],  // Đảm bảo đã có giá trị trong $_SESSION
        $data['section_id'],
        $data['correct_count'],
        $data['total_questions']
    ]);
    

    // Lấy ID của kết quả quiz vừa chèn
    $quiz_result_id = $pdo->lastInsertId();

    // Duyệt qua từng câu trả lời người dùng
    $answers = $data['answers'];

    foreach ($answers as $ans) {
        $question_id = $ans['question_id'];
        $user_answer = trim(strtolower($ans['answer']));

        // Lấy đáp án đúng từ DB
        $stmt = $pdo->prepare("SELECT answer FROM quiz_answers WHERE question_id = ? AND is_correct = 1 LIMIT 1");
        $stmt->execute([$question_id]);
        $correct = trim(strtolower($stmt->fetchColumn()));

        $is_correct = ($user_answer === $correct) ? 1 : 0;

        // Chèn kết quả chi tiết cho mỗi câu hỏi vào bảng quiz_history
        $stmt = $pdo->prepare("INSERT INTO quiz_history (quiz_result_id, question_id, user_answer, is_correct)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$quiz_result_id, $question_id, $user_answer, $is_correct]);
    }

    // Commit giao dịch sau khi tất cả truy vấn thành công
    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Nếu có lỗi, rollback giao dịch
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Có lỗi xảy ra', 'message' => $e->getMessage()]);
}
?>
