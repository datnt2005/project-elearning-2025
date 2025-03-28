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
        renderViewAdmin("view/admin/post/category/index.php", compact('postCategories'), "Danh sách danh mục bài viết");
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
                    header("Location: /admin/postCategory");  
                    exit;

                } else {
                    $errors[] = "Lỗi! Không thể thêm danh mục.";
                }
            }
        }
    
        renderViewAdmin("view/admin/post/category/create.php", compact('errors'), "Thêm danh mục mới");
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
                    header("Location: /admin/postCategory");  
                    exit;
                } else {
                    $errors[] = "Lỗi! Không thể cập nhật danh mục.";
                }
            }
        }
    
        renderViewAdmin("view/admin/post/category/edit.php", compact('category', 'errors'), "Chỉnh sửa danh mục");
    }
    
    public function delete($id)
{
    $category = $this->postCategory->getCategoryById($id);

    if (!$category || isset($category['error'])) {
        header("Location: /admin/postCategory");  
        exit;
    }

    $result = $this->postCategory->deleteCategory($id);

    if ($result) {
        $_SESSION["success_message"] = "Xóa danh mục thành công!";
    } else {
        $_SESSION["error_message"] = "Lỗi! Không thể xóa danh mục.";
    }

    header("Location: /admin/postCategory");  
    exit;
}

   
}
