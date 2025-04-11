<?php
require_once "model/Post.php";
require_once "model/UserModel.php";
require_once "model/NotificationModel.php";
require_once "model/PostCategory.php";
require_once "view/helpers.php";

class PostController
{
    private $post;
    private $userModel;
    private $notificationModel;

    public function __construct()
    {
        $this->post = new Post();
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $posts = $this->post->getAllPosts();
        if (!is_array($posts)) {
            $posts = [];
        }

        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/post/index.php", compact('posts'), "Danh sách bài viết");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/post/index.php", compact('posts'), "Danh sách bài viết");
        }
    }

    public function show()
    {
        $posts = $this->post->getAllPosts();
    
        if (empty($posts)) {
            $_SESSION["error_message"] = "Chưa có bài viết nào!";
            header("Location: /");
            exit;
        }
    
        renderViewUser("view/users/post/index.php", compact('posts'), "Danh sách bài viết");
    }
    public function detail($id)
{
    // Lấy thông tin bài viết chính
    $post = $this->post->getPostById($id);
    if (!$post) {
        $_SESSION["error_message"] = "Bài viết không tồn tại!";
        header("Location: /posts");
        exit;
    }

    // Lấy bài viết liên quan (cùng danh mục, loại trừ bài viết hiện tại)
    $relatedPosts = $this->post->getRelatedPosts($post['category_id'], $id);

    // Truyền cả bài viết chính và bài viết liên quan vào view
    renderViewUser("view/users/post/detail.php", compact('post', 'relatedPosts'), $post['title']);
    
}

    // Tạo bài viết mới
    public function create()
{
    $categories = $this->post->getAllCategories();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = trim($_POST["title"]);
        $category = trim($_POST["category"]);
        $content = trim($_POST["content"]);
        $userId = $_SESSION["user"]["id"]; // Admin đang đăng nhập

        if (empty($title) || empty($content)) {
            $_SESSION["error"] = "Tiêu đề và nội dung không được để trống.";
            header("Location: /admin/post/create");
            exit;
        }

        // Xử lý ảnh đại diện
        $thumbnail = null;
        if (!empty($_FILES["thumbnail"]["name"])) {
            $uploadDir = "uploads/";
            $fileName = time() . "_" . basename($_FILES["thumbnail"]["name"]);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFile)) {
                $thumbnail = $fileName;
            } else {
                $_SESSION["error"] = "Lỗi khi tải ảnh lên.";
                header("Location: /admin/post/create");
                exit;
            }
        }

        $result = $this->post->createPost($userId, $title, $category, $content, $thumbnail);

        if ($result) {
            // ✅ Gửi thông báo cho tất cả user có role là student
            $message = "📝 Bài viết mới: $title. Hãy khám phá ngay!";
            $link = "/posts/detail/$result"; // Hoặc chi tiết bài viết nếu bạn có slug/id
            $adminId = $userId;

            // Lấy danh sách tất cả user role student
            $students = $this->userModel->getAllUserRole();
            foreach ($students as $student) {
                $this->notificationModel->createAutoNotification($student['id'], $message, $link, $adminId);
            }

            $_SESSION["success"] = "Bài viết đã được tạo thành công.";
            header("Location: /admin/post");
            exit;
        } else {
            header("Location: /admin/post/create");
            exit;
        }
    }

    renderViewAdmin("view/admin/post/create.php", compact('categories'), "Viết bài mới");
}



    // Chỉnh sửa bài viết
    public function edit($id)
    {
        $post = $this->post->getPostById($id);
        $categories = $this->post->getAllCategories();
        $errors = [];
    
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = trim($_POST["title"]);
            $category = trim($_POST["category"]);
            $content = trim($_POST["content"]);
            
            $thumbnail = $post['thumbnail'];  
            if (!empty($_FILES["thumbnail"]["name"])) {
                $uploadDir = "uploads/";
                $fileName = time() . "_" . basename($_FILES["thumbnail"]["name"]);
                $targetFile = $uploadDir . $fileName;
    
                if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFile)) {
                    $thumbnail = $fileName;
                } else {
                    $errors[] = "Lỗi khi tải ảnh lên.";
                }
            }
    
            if (empty($errors)) {
                $result = $this->post->updatePost($id, $title, $category, $content, $thumbnail);
                if ($result) {
                    $_SESSION["success"] = "Bài viết đã được cập nhật.";
                    // Chuyển hướng về danh sách bài viết
                    if ($_SESSION['user']['role'] === 'admin') {
                        $_SESSION["success"] = "Bài viết đã được cập nhật.";
                        header("Location: /admin/post");
                    } else if ($_SESSION['user']['role'] === 'instructor') {
                        $_SESSION["success"] = "Bài viết đã được cập nhật.";
                        header("Location: /instructor/post");
                    }
                    exit;
                } else {
                    $errors[] = "Lỗi! Không thể cập nhật bài viết.";
                }
            }
        }
    
        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/post/edit.php", compact('post', 'categories', 'errors'), "Chỉnh sửa bài viết");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/post/edit.php", compact('post', 'categories', 'errors'), "Chỉnh sửa bài viết");
        } 
    }
    

    // Xóa bài viết
    public function delete($id)
    {
        $post = $this->post->getPostById($id);

        if (!$post || isset($post['error'])) {
            $_SESSION["error_message"] = "Bài viết không tồn tại!";
            if ($_SESSION['user']['role'] === 'admin') {
                header("Location: /admin/posts");
            } else if ($_SESSION['user']['role'] === 'instructor') {
                header("Location: /instructor/posts");
            }
            exit;
        }

        $result = $this->post->deletePost($id);

        if ($result) {
            $_SESSION["success_message"] = "Xóa bài viết thành công!";
        } else {
            $_SESSION["error_message"] = "Lỗi! Không thể xóa bài viết.";
        }

        // Chuyển hướng về danh sách bài viết
        if ($_SESSION['user']['role'] === 'admin') {
            header("Location: /admin/post");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            header("Location: /instructor/post");
        }
         // Đảm bảo không bị lỗi undefined variable
        exit;
    }
 
 
    
}
