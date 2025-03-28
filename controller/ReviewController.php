<?php
require_once "model/ReviewModel.php";
require_once "model/OrderModel.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ReviewController
{
    private $reviewModel;
    private $orderModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->orderModel = new OrderModel();
    }


    public function addReview()
    {
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập"]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $courseId = $_POST['course_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? null;

        // Kiểm tra thiếu thông tin
        if (!$courseId || !$rating || !$comment) {
            echo json_encode(["status" => "error", "message" => "Thiếu thông tin cần thiết"]);
            exit;
        }

        // Kiểm tra nếu đã có đánh giá, thì cập nhật thay vì thêm mới
        $existingReview = $this->reviewModel->hasUserReviewedCourse($userId, $courseId);
        if ($existingReview) {
            // Gọi hàm updateReview thay vì thêm mới
            $_POST['review_id'] = $existingReview['id'];
            return $this->editReview();
        }

        // Thêm đánh giá vào database
        $reviewId = $this->reviewModel->addReview($courseId, $userId, $rating, $comment);

        // Xử lý upload ảnh đánh giá
        $imagePaths = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = "uploads/reviews/" . uniqid() . "_" . basename($_FILES['images']['name'][$key]);
                move_uploaded_file($tmpName, $fileName);
                $imagePaths[] = $fileName;
            }
            $this->reviewModel->addReviewImages($reviewId, $imagePaths);
        }

        echo json_encode(["status" => "success", "message" => "Đánh giá thành công"]);
        exit;
    }



    public function editReview()
    {
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập"]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $reviewId = $_POST['review_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? null;

        if (!$reviewId || !$rating || !$comment) {
            echo json_encode(["status" => "error", "message" => "Thiếu thông tin cần thiết"]);
            exit;
        }

        // Cập nhật nội dung đánh giá
        if (!$this->reviewModel->updateReview($reviewId, $userId, $rating, $comment)) {
            echo json_encode(["status" => "error", "message" => "Lỗi khi cập nhật đánh giá"]);
            exit;
        }

        // Xử lý upload ảnh mới
        if (!empty($_FILES['images']['name'][0])) {
            // Xóa ảnh cũ nếu có
            $this->reviewModel->deleteReviewImages($reviewId);

            $imagePaths = [];
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = "/uploads/reviews/" . uniqid() . "_" . basename($_FILES['images']['name'][$key]);
                move_uploaded_file($tmpName, $fileName);
                $imagePaths[] = $fileName;
            }
            // Cập nhật ảnh mới vào database
            $this->reviewModel->addReviewImages($reviewId, $imagePaths);
        }

        echo json_encode(["status" => "success", "message" => "Đánh giá đã cập nhật"]);
        exit;
    }



    public function deleteReview()
    {
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(["status" => "error", "message" => "VMLINUX"]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $reviewId = $_POST['review_id'] ?? null;

        if (!$reviewId) {
            echo json_encode(["status" => "error", "message" => "Thiếu review_id"]);
            exit;
        }

        if (!$this->reviewModel->deleteReview($reviewId)) {
            echo json_encode(["status" => "error", "message" => "Lỗi khóa học"]);
            exit;
        }

        echo json_encode(["status" => "success", "message" => "Đánh giá được xóa"]);
        exit;
    }

    public function getReviews()
    {
        $courseId = $_GET['course_id'] ?? null;

        if (!$courseId) {
            echo json_encode(["status" => "error", "message" => "Thiếu course_id"]);
            exit;
        }

        // Lấy ID người dùng (nếu đã đăng nhập)
        $userId = $_SESSION['user']['id'] ?? null;

        // Lấy danh sách đánh giá
        $reviews = $this->reviewModel->getReviewsByCourseId($courseId, $userId);

        // Thêm danh sách phản hồi vào từng đánh giá
        foreach ($reviews as &$review) {
            $review['replies'] = $this->reviewModel->getRepliesByReviewId($review['id']);
        }

        echo json_encode(["status" => "success", "reviews" => $reviews]);
        exit;
    }



    public function toggleLikeReview()
    {
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập để like"]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $reviewId = $_POST['review_id'] ?? null;

        if (!$reviewId) {
            echo json_encode(["status" => "error", "message" => "Thiếu review_id"]);
            exit;
        }

        $likeExists = $this->reviewModel->checkUserLike($userId, $reviewId);

        if ($likeExists) {
            // Nếu đã like thì hủy like
            $this->reviewModel->removeLike($userId, $reviewId);
            $status = "unliked";
        } else {
            // Nếu chưa like thì thêm like
            $this->reviewModel->addLike($userId, $reviewId);
            $status = "liked";
        }

        // Đếm lại số lượng like
        $likeCount = $this->reviewModel->countLikes($reviewId);

        echo json_encode([
            "status" => $status,
            "like_count" => $likeCount
        ]);

        exit;
    }

    public function replyReview()
    {
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(["status" => "error", "message" => "Bạn cần đăng nhập"]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'] ?? null;

        if ($role !== 'admin') {
            echo json_encode(["status" => "error", "message" => "Bạn không có quyền trả lời!"]);
            exit;
        }

        $reviewId = $_POST['review_id'] ?? null;
        $comment = $_POST['comment'] ?? null;

        if (!$reviewId || !$comment) {
            echo json_encode(["status" => "error", "message" => "Thiếu thông tin cần thiết"]);
            exit;
        }

        // Thêm phản hồi vào database
        $replyId = $this->reviewModel->addReviewReply($reviewId, $userId, $comment);

        // Lấy phản hồi mới
        $reply = $this->reviewModel->getReplyById($replyId);

        echo json_encode([
            "status" => "success",
            "reply" => [
                "id" => $reply['id'],
                "admin_name" => $reply['admin_name'],
                "comment" => $reply['comment'],
                "created_at" => $reply['created_at']
            ]
        ]);
        exit;
    }


    public function getReviewReplies()
    {
        $reviewId = $_GET['review_id'] ?? null;

        if (!$reviewId) {
            echo json_encode(["status" => "error", "message" => "Thiếu review_id"]);
            exit;
        }

        $replies = $this->reviewModel->getRepliesByReviewId($reviewId);

        echo json_encode([
            "status" => "success",
            "replies" => $replies
        ]);
        exit;
    }
}
