<?php
require_once "database.php";

class ReviewModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    //Thêm đánh giá mới
    public function addReview($courseId, $userId, $rating, $comment)
    {
        $query = "INSERT INTO reviews (user_id, course_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $courseId, $rating, $comment]);
        return $this->conn->lastInsertId();
    }

    public function addReviewImages($reviewId, $imagePaths)
    {
        $query = "INSERT INTO review_images (review_id, image_path) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        foreach ($imagePaths as $imagePath) {
            $stmt->execute([$reviewId, $imagePath]);
        }
    }

    public function updateReview($reviewId, $userId, $rating, $comment)
    {
        $query = "UPDATE reviews SET user_id = ?, rating = ?, comment = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $rating, $comment, $reviewId]);
        return $stmt->rowCount() > 0;
    }

    public function updateReviewImages($reviewId, $imagePaths)
    {
        $query = "DELETE FROM review_images WHERE review_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reviewId]);

        $query = "INSERT INTO review_images (review_id, image_path) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        foreach ($imagePaths as $imagePath) {
            $stmt->execute([$reviewId, $imagePath]);
        }
    }

    public function deleteReview($reviewId)
    {
        $query = "DELETE FROM reviews WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reviewId]);
        return $stmt->rowCount() > 0;
    }

    public function deleteReviewImages($reviewId)
    {
        $query = "DELETE FROM review_images WHERE review_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reviewId]);
        return $stmt->rowCount() > 0;
    }

    public function hasUserReviewedCourse($userId, $courseId)
    {
        $query = "SELECT COUNT(*) FROM reviews WHERE user_id = ? AND course_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $courseId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getReviewsByCourseId($courseId, $userId)
    {
        $query = "SELECT r.*, u.name AS user_name, 
                         (SELECT COUNT(*) FROM review_likes WHERE review_id = r.id) AS like_count,
                         (SELECT COUNT(*) FROM review_likes WHERE review_id = r.id AND user_id = ?) AS liked
                  FROM reviews r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.course_id = ?
                  ORDER BY r.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $courseId]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy ảnh cho từng review
        foreach ($reviews as &$review) {
            $query = "SELECT image_path FROM review_images WHERE review_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$review['id']]);
            $review['images'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Chuyển đổi `liked` thành boolean
            $review['liked'] = $review['liked'] > 0;
        }

        return $reviews;
    }

    // Lấy thông tin đánh giá
    public function getReviewById($reviewId)
    {
        $query = "SELECT * FROM reviews WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reviewId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Kiểm tra xem người dùng đã like review chưa
    public function checkUserLike($userId, $reviewId)
    {
        $query = "SELECT id FROM review_likes WHERE user_id = ? AND review_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $reviewId]);
        return $stmt->fetchColumn(); // Trả về ID nếu đã like, null nếu chưa
    }

    // Thêm like vào database
    public function addLike($userId, $reviewId)
    {
        $query = "INSERT INTO review_likes (user_id, review_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId, $reviewId]);
    }

    // Xóa like khỏi database (bỏ like)
    public function removeLike($userId, $reviewId)
    {
        $query = "DELETE FROM review_likes WHERE user_id = ? AND review_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$userId, $reviewId]);
    }

    // Đếm số lượng like của một review
    public function countLikes($reviewId)
    {
        $query = "SELECT COUNT(*) FROM review_likes WHERE review_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reviewId]);
        return $stmt->fetchColumn(); // Trả về số lượng like
    }



    public function addReviewReply($reviewId, $userId, $comment)
    {
        $query = "INSERT INTO review_replies (review_id, user_id, comment) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reviewId, $userId, $comment]);
        return $this->conn->lastInsertId();
    }

    // Lấy thông tin user theo ID
    public function getUserById($userId)
    {
        $query = "SELECT id, name FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReplyById($replyId)
    {
        $sql = "SELECT r.id, r.comment, r.created_at, u.name as admin_name 
            FROM review_replies r 
            JOIN users u ON r.user_id = u.id
            WHERE r.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$replyId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getRepliesByReviewId($reviewId)
    {
        $query = "SELECT r.id, r.review_id, r.user_id, u.name AS admin_name, r.comment, r.created_at 
              FROM review_replies r 
              JOIN users u ON r.user_id = u.id 
              WHERE r.review_id = ? 
              ORDER BY r.created_at ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$reviewId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
