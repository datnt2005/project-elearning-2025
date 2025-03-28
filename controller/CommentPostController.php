<?php
require_once "model/Post.php";
require_once "model/PostCategory.php";
require_once "model/CommentPost.php";
require_once "view/helpers.php";

class CommentPostController
{
    private $post;
    private $comment;

    public function __construct()
    {
        $this->post = new Post();
        $this->comment = new CommentPost();
    }

    // Thêm bình luận
    public function addComment() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Phương thức không hợp lệ.");
            }
    
            // Kiểm tra người dùng đã đăng nhập
            if (!isset($_SESSION['user']['id'])) {
                throw new Exception("Bạn cần đăng nhập để bình luận.");
            }
    
            $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
            $content = trim(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $userId = $_SESSION['user']['id'];
    
            // Kiểm tra đầu vào hợp lệ
            if (!$postId || !$userId || empty($content)) {
                throw new Exception("Dữ liệu không hợp lệ. post_id: $postId, user_id: $userId, content: '$content'");
            }
    
            // Kiểm tra bài viết có tồn tại không
            $post = $this->post->getPostById($postId);
            if (!$post) {
                throw new Exception("Bài viết không tồn tại. post_id: $postId");
            }
    
            // Thêm bình luận
            if (!$this->comment->addComment($postId, $userId, $content)) {
                throw new Exception("Không thể thêm bình luận. post_id: $postId, user_id: $userId, content: '$content'");
            }
    
            echo json_encode(["success" => "Bình luận đã được thêm thành công."]);
            header("Location: /posts/detail/$postId");
            exit;
        } catch (Exception $e) {
            error_log("Lỗi trong addComment: " . $e->getMessage());
            echo json_encode(["error" => $e->getMessage()]);
            exit;
        }
    }
    
    // Lấy danh sách bình luận theo bài viết
    public function getComments($postId) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception("Phương thức không hợp lệ.");
            }
    
            // Kiểm tra post_id hợp lệ
            $postId = filter_var($postId, FILTER_VALIDATE_INT);
            if (!$postId) {
                throw new Exception("Bài viết không hợp lệ.");
            }
    
            // Lấy bình luận từ bài viết
            $comments = $this->comment->getCommentsByPostId($postId);
    
            echo json_encode($comments);
            exit;
        } catch (Exception $e) {
            error_log("Lỗi trong getComments: " . $e->getMessage());
            echo json_encode(["error" => $e->getMessage()]);
            exit;
        }
    }
    
    public function deleteComment($commentId) {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Chưa đăng nhập.']);
            exit;
        }
    
        // Kiểm tra quyền của người dùng (chỉ cho phép chủ bình luận xoá)
        $comment = $this->comment->getCommentById($commentId);
        if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'Không thể xoá bình luận.']);
            exit;
        }
    
        // Thực hiện xoá bình luận
        if ($this->comment->deleteComment($commentId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Xoá bình luận thất bại.']);
        }
    
        exit;
    }
    
    
}
?>