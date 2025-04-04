<?php
require_once "Database.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class LessonProgressModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getProgress($user_id, $lesson_id)
    {
        $query = "SELECT progress, completed FROM lesson_progress WHERE user_id = :user_id AND lesson_id = :lesson_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['progress' => 0, 'completed' => false];
    }

    public function updateProgress($userId, $lessonId, $progress, $completed = false)
    {
        file_put_contents('debug.log', "Received data: " . print_r(['user_id' => $userId, 'lesson_id' => $lessonId, 'progress' => $progress, 'completed' => $completed], true) . "\n", FILE_APPEND);
        $query = "INSERT INTO lesson_progress (user_id, lesson_id, progress, completed, last_watched_at) 
                  VALUES (:user_id, :lesson_id, :progress, :completed, NOW()) 
                  ON DUPLICATE KEY UPDATE progress = :progress, completed = :completed, last_watched_at = NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':lesson_id', $lessonId, PDO::PARAM_INT);
        $stmt->bindParam(':progress', $progress, PDO::PARAM_STR);
        $stmt->bindParam(':completed', $completed, PDO::PARAM_BOOL);
        $success = $stmt->execute();
        if ($success) {
            file_put_contents('debug.log', "Progress updated successfully for user_id: $userId, lesson_id: $lessonId\n", FILE_APPEND);
        } else {
            file_put_contents('debug.log', "Failed to update progress for user_id: $userId, lesson_id: $lessonId\n", FILE_APPEND);
        }
        return $success;
    }

    public function calculateCourseProgress($userId, $courseId)
    {
        $query = "SELECT COUNT(*) AS total_lessons FROM lessons l JOIN sections s ON l.section_id = s.id WHERE s.course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        $totalLessons = $stmt->fetch(PDO::FETCH_ASSOC)['total_lessons'];

        file_put_contents('debug.log', "Total lessons for course $courseId: $totalLessons\n", FILE_APPEND);

        if ($totalLessons == 0) return 0;

        $query = "SELECT COUNT(*) AS completed_lessons FROM lesson_progress lp 
                  JOIN lessons l ON lp.lesson_id = l.id
                  JOIN sections s ON l.section_id = s.id
                  WHERE lp.user_id = :user_id AND lp.completed = 1 AND s.course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        $completedLessons = $stmt->fetch(PDO::FETCH_ASSOC)['completed_lessons'];

        file_put_contents('debug.log', "Completed lessons for user $userId, course $courseId: $completedLessons\n", FILE_APPEND);

        $progress = ($completedLessons / $totalLessons) * 100;
        file_put_contents('debug.log', "Calculated course progress for user $userId, course $courseId: $progress%\n", FILE_APPEND);
        return $progress;
    }

    public function getEnrollment($userId, $courseId)
    {
        $query = "SELECT progress, status FROM enrollments WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateEnrollment($userId, $courseId, $enrollment_date, $status, $progress)
    {
        file_put_contents('debug.log', "Updating enrollment: " . print_r(['user_id' => $userId, 'course_id' => $courseId, 'enrollment_date' => $enrollment_date, 'status' => $status, 'progress' => $progress], true) . "\n", FILE_APPEND);

        // Câu lệnh SQL đảm bảo chỉ cập nhật nếu bản ghi đã tồn tại, nếu không thì chèn mới
        $query = "INSERT INTO enrollments (user_id, course_id, enrollment_date, status, progress) 
                  VALUES (:user_id, :course_id, :enrollment_date, :status, :progress) 
                  ON DUPLICATE KEY UPDATE progress = :progress, status = :status, enrollment_date = :enrollment_date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':enrollment_date', $enrollment_date, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':progress', $progress, PDO::PARAM_STR);
        $success = $stmt->execute();

        if ($success) {
            file_put_contents('debug.log', "Enrollment updated successfully for user_id: $userId, course_id: $courseId\n", FILE_APPEND);
        } else {
            file_put_contents('debug.log', "Failed to update enrollment for user_id: $userId, course_id: $courseId\n", FILE_APPEND);
        }
        return $success;
    }

    public function getCourseCompletedLessons($userId, $courseId)
    {
        $query = "SELECT COUNT(*) AS completed_lessons FROM lesson_progress lp 
                  JOIN lessons l ON lp.lesson_id = l.id
                  JOIN sections s ON l.section_id = s.id
                  WHERE lp.user_id = :user_id AND lp.completed = 1 AND s.course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['completed_lessons'];
    }

    public function getCourseProgress($userId, $courseId)
    {
        $query = "SELECT progress FROM enrollments WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['progress'];
    }

    public function getCourseCompleted($userId, $courseId)
    {
        $query = "SELECT status FROM enrollments WHERE user_id = :user_id AND course_id = :course_id ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['status'];
    }

    // chung nhanj
    public function getCertificate($userId, $courseId)
    {
        $query = "SELECT certificate FROM enrollments WHERE user_id = :user_id AND course_id = :course_id ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['certificate'];
    }

    public function issueCertificate($userId, $courseId)
    {
        // Kiểm tra xem đã có chứng chỉ chưa
        $query = "SELECT id FROM certificates WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch()) {
            return ['status' => 'exists', 'message' => 'Certificate already issued'];
        }

        // Lấy email người dùng
        $query = "SELECT email FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }
        $userEmail = $user['email'];

        // Lấy tên khóa học
        $query = "SELECT title FROM courses WHERE id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        $courseTitle = $course ? $course['title'] : 'Khóa học không xác định';

        // Sinh mã chứng chỉ
        $certificateCode = 'CERT-U' . $userId . '-C' . $courseId . '-' . date('Ymd');
        $certificateUrl = '/certificates/view/' . $certificateCode;

        // Lưu chứng chỉ vào database
        $query = "INSERT INTO certificates (user_id, course_id, certificate_code, certificate_url, issued_at, status) 
                  VALUES (:user_id, :course_id, :certificate_code, :certificate_url, NOW(), 'active')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':certificate_code', $certificateCode, PDO::PARAM_STR);
        $stmt->bindParam(':certificate_url', $certificateUrl, PDO::PARAM_STR);
        $success = $stmt->execute();

        if (!$success) {
            return ['status' => 'error', 'message' => 'Failed to issue certificate'];
        }

        // Gửi email thông báo
        $this->sendCertificateEmail($userEmail, $certificateCode, $certificateUrl, $courseTitle);

        return ['status' => 'success', 'certificate_code' => $certificateCode, 'certificate_url' => $certificateUrl];
    }

    private function sendCertificateEmail($toEmail, $certificateCode, $certificateUrl, $courseTitle)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD']; // Mật khẩu ứng dụng
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];
            // Người gửi và người nhận
            $mail->CharSet = "UTF-8";
            $mail->setFrom($_ENV['MAIL_USERNAME'], 'Passion');
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Chúc mừng! Bạn đã nhận được chứng chỉ hoàn thành khóa học';
            $mail->Body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;'>
        <div style='text-align: center;'>
            <img src='https://yourwebsite.com/logo.png' alt='Logo' style='max-width: 120px; margin-bottom: 20px;'>
            <h2 style='color: #2c3e50;'>🎉 Chúc mừng bạn đã hoàn thành khóa học! 🎉</h2>
        </div>
        
        <div style='background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);'>
            <p style='font-size: 16px; color: #333;'>Xin chào <strong>Thượng Đế</strong>,</p>
            <p style='font-size: 16px; color: #555;'>Bạn đã hoàn thành khóa học: <br><strong style='color: #2980b9; font-size: 18px;'>$courseTitle</strong></p>
            
            <p style='font-size: 16px; color: #333;'>🎓 <strong>Mã chứng chỉ:</strong> 
                <span style='color: #e67e22; font-weight: bold;'>$certificateCode</span>
            </p>

            <p style='font-size: 16px; color: #333;'>Bạn có thể xem và tải chứng chỉ của mình tại:</p>
            <p style='text-align: center;'>
                <a href='http://localhost:8000/certificate?certificate_url=$certificateUrl' style='display: inline-block; background: #3498db; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold;'>
                    📜 Xem Chứng Chỉ
                </a>
            </p>

            <p style='font-size: 16px; color: #555;'>Cảm ơn bạn đã tham gia khóa học của chúng tôi! Chúc bạn tiếp tục học tập hiệu quả và thành công. 🚀</p>
        </div>

        <div style='text-align: center; margin-top: 20px; font-size: 14px; color: #777;'>
            <p>&copy; 2025 Hệ thống đào tạo | <a href='https://yourwebsite.com' style='color: #2980b9; text-decoration: none;'>Truy cập website</a></p>
        </div>
    </div>
";

            $mail->AltBody = "Chúc mừng bạn đã hoàn thành khóa học $courseTitle. Mã chứng chỉ: $certificateCode. Xem tại: $certificateUrl";

            $mail->send();
            file_put_contents('debug.log', "Email sent to $toEmail with certificate $certificateCode\n", FILE_APPEND);
        } catch (Exception $e) {
            file_put_contents('debug.log', "Failed to send email to $toEmail: {$mail->ErrorInfo}\n", FILE_APPEND);
        }
    }

    public function getCertificateByCode($certificate_url) {
        $query = "SELECT 
                    *, 
                    us.name as user_name, 
                    ins.name as instructor_name
                  FROM certificates ce 
                  JOIN courses co ON ce.course_id = co.id
                  JOIN users us ON ce.user_id = us.id
                  JOIN users ins ON co.instructor_id = ins.id  -- Lấy giảng viên từ users
        
                  WHERE ce.certificate_url = :certificate_url 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':certificate_url', $certificate_url, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function checkCertificateCourse($user_id, $course_id) {
        $query = "SELECT * FROM certificates WHERE user_id = :user_id AND course_id = :course_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }
    public function __destruct()
    {
        $this->conn = null;
    }
}