<?php
class CommentPost
{
    private $conn;

    public function __construct()
    {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();
        } catch (PDOException $e) {
            die("Lỗi kết nối Database: " . $e->getMessage());
        }
    }

    // Kiểm tra bài viết có tồn tại không
    private function postExists($postId) {
        $sql = "SELECT id FROM posts WHERE id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Thêm bình luận mới
    public function addComment($postId, $userId, $content) {
        try {
            $sql = "INSERT INTO post_comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    
            $result = $stmt->execute();
    
            if (!$result) {
                throw new Exception("Lỗi SQL: " . implode(", ", $stmt->errorInfo()));
            }
    
            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi khi thêm bình luận: " . $e->getMessage());
            throw new Exception("Lỗi khi thêm bình luận: " . $e->getMessage());
        }
    }
    

    // Lấy danh sách bình luận theo bài viết
    public function getCommentsByPostId($postId) {
        $stmt = $this->conn->prepare("SELECT c.*, u.name FROM post_comments c 
                                    JOIN users u ON c.user_id = u.id 
                                    WHERE c.post_id = :post_id 
                                    ORDER BY c.created_at DESC");
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentById($commentId) {
        $stmt = $this->conn->prepare("SELECT * FROM post_comments WHERE id = :id");
        $stmt->bindParam(":id", $commentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Xoá bình luận
    public function deleteComment($commentId) {
        $stmt = $this->conn->prepare("DELETE FROM post_comments WHERE id = :id");
        $stmt->bindParam(":id", $commentId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
