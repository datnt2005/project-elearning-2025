<?php
require_once "model/PostCategory.php";
require_once "view/helpers.php";

class PostCategoryController
{
    private $postCategory;


    public function __construct()
    {
        $this->postCategory = new PostCategory();
    }

    // Lấy danh sách danh mục
    public function index()
    {
        $postCategories = $this->postCategory->getAllCategories();
        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/post/category/index.php", compact('postCategories'), "Danh sách danh mục bài viết");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/post/category/index.php", compact('postCategories'), "Danh sách danh mục bài viết");
        } 
    }

    public function create()
    {
        $errors = [];
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
            $name = trim($_POST["name"]);
    
            if (empty($name)) {
                $errors[] = "Tên danh mục không được để trống.";
            }
    
            if (empty($errors)) {
                $result = $this->postCategory->createCategory($name);
                if ($result) {
                    $_SESSION["success_message"] = "Thêm danh mục thành công!"; 
                    // Chuyển hướng về trang danh sách danh mục
                    if ($_SESSION['user']['role'] === 'admin') {
                        header("Location: /admin/postCategory");  
                    } else if ($_SESSION['user']['role'] === 'instructor') {
                        header("Location: /instructor/postCategory");  
                    }
                    exit;

                } else {
                    $errors[] = "Lỗi! Không thể thêm danh mục.";
                }
            }
        }
    
        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/post/category/create.php", compact('errors'), "Thêm danh mục bài viết");

        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/post/category/create.php", compact('errors'), "Thêm danh mục bài viết");
        } 
        
        
    }

    
    
    public function edit($id)
    {
        $errors = [];
        $category = $this->postCategory->getCategoryById($id);
    
        if (!$category || isset($category['error'])) {
            header("Location: /admin/postCategory"); 
            exit;
        }
    
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = trim($_POST["name"]);
    
            if (empty($name)) {
                $errors[] = "Tên danh mục không được để trống.";
            }
    
            if (empty($errors)) {
                $result = $this->postCategory->updateCategory($id, $name);
                if ($result) {
                    $_SESSION["success_message"] = "Cập nhật danh mục thành công!";
                    // Chuyển hướng về trang danh sách danh mục
                    if ($_SESSION['user']['role'] === 'admin') {
                        header("Location: /admin/postCategory");  
                    } else if ($_SESSION['user']['role'] === 'instructor') {
                        header("Location: /instructor/postCategory");  
                    }
                    exit;
                } else {
                    $errors[] = "Lỗi! Không thể cập nhật danh mục.";
                }
            }
        }
    
        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/post/category/edit.php", compact('category', 'errors'), "Chỉnh sửa danh mục bài viết");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/post/category/edit.php", compact('category', 'errors'), "Chỉnh sửa danh mục bài viết");
        }        
    }
    
    public function delete($id)
{
    $category = $this->postCategory->getCategoryById($id);

    if (!$category || isset($category['error'])) {
        $_SESSION["error_message"] = "Danh mục không tồn tại!";
        // Chuyển hướng về trang danh sách danh mục
        if ($_SESSION['user']['role'] === 'admin') {
            header("Location: /admin/postCategory");  
        } else if ($_SESSION['user']['role'] === 'instructor') {
            header("Location: /instructor/postCategory");  
        }
        exit;
    }

    $result = $this->postCategory->deleteCategory($id);

    if ($result) {
        $_SESSION["success_message"] = "Xóa danh mục thành công!";
    } else {
        $_SESSION["error_message"] = "Lỗi! Không thể xóa danh mục.";
    }
    if  ($_SESSION['user']['role'] === 'admin') {
        header("Location: /admin/postCategory");  
    } else if ($_SESSION['user']['role'] === 'instructor') {
        header("Location: /instructor/postCategory");  
    }
    exit;
}

   
}
