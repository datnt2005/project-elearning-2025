<?php
require_once "Database.php";

class CertificateModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    // chung nhanj
    public function getCertificate($userId, $courseId) {
        $query = "SELECT certificate FROM enrollments WHERE user_id = :user_id AND course_id = :course_id ";
        $stmt = $this->conn->prepare($query);    
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['certificate'];
    }
    
    public function addCertificate($userId, $courseId, $certificate) {
        $query = "INSERT INTO enrollments (user_id, course_id, certificate) VALUES (:user_id, :course_id, :certificate)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_STR);
        $stmt->execute();
    }
    public function updateCertificate($userId, $courseId, $certificate) {
        $query = "UPDATE enrollments SET certificate = :certificate WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
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

        // Sinh mã chứng chỉ
        $certificateCode = 'CERT-U' . $userId . '-C' . $courseId . '-' . date('Ymd');
        $certificateUrl = '/certificates/view/' . $certificateCode; // Ví dụ URL

        $query = "INSERT INTO certificates (user_id, course_id, certificate_code, certificate_url, issued_at, status) 
                  VALUES (:user_id, :course_id, :certificate_code, :certificate_url, NOW(), 'active')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':certificate_code', $certificateCode, PDO::PARAM_STR);
        $stmt->bindParam(':certificate_url', $certificateUrl, PDO::PARAM_STR);
        $success = $stmt->execute();

        return $success ? ['status' => 'success', 'certificate_code' => $certificateCode, 'certificate_url' => $certificateUrl] : ['status' => 'error', 'message' => 'Failed to issue certificate'];
    }
}