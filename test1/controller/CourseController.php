<?php

// Dùng đường dẫn tuyệt đối (__DIR__) để tránh lỗi
require_once __DIR__ . "/../model/CourseModel.php";
require_once __DIR__ . "/../view/helpers.php";

class CourseController
{
    private $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
    }

    // GET: /admin/courses -> Danh sách
    public function index()
    {
        $courses = $this->courseModel->getAllCourses();
        // Hiển thị danh sách ở view/courses/list.php
        renderViewAdmin("view/courses/list.php", ["courses" => $courses], "Course List");
    }

    // GET: /admin/courses/create -> Form tạo course
    public function createForm()
    {
        renderViewAdmin("view/courses/create.php", [], "Create Course");
    }

    // POST: /admin/courses/store -> Xử lý lưu course
    public function store()
    {
        try {
            // Lấy dữ liệu text
            $title          = $_POST['title']          ?? '';
            $description    = $_POST['description']    ?? '';
            $instructor_id  = $_POST['instructor_id']  ?? 0;
            $price          = $_POST['price']          ?? 0;
            $discount_price = $_POST['discount_price'] ?? 0;
            $duration       = $_POST['duration']       ?? 0;
            $status         = $_POST['status']         ?? 'inactive';
            $category_id    = $_POST['category_id']    ?? 0;
            $subcategory_id = $_POST['subcategory_id'] ?? 0;

            // Upload file image (nếu user chọn)
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir  = "uploads/";
                $fileName   = time() . "_" . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $fileName;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                $imagePath  = $targetFile;
            }

            // Upload/hoặc nhận URL video intro
            $videoPath = '';
            if (isset($_FILES['video_intro']) && $_FILES['video_intro']['error'] === 0) {
                // User upload file video
                $targetDir  = "uploads/videos/";
                $fileName   = time() . "_" . basename($_FILES['video_intro']['name']);
                $targetFile = $targetDir . $fileName;
                move_uploaded_file($_FILES['video_intro']['tmp_name'], $targetFile);
                $videoPath  = $targetFile;
            } else {
                // User nhập URL thay vì upload
                $videoPath = $_POST['video_intro'] ?? '';
            }

            // Gọi model -> create
            $this->courseModel->create(
                $title,
                $description,
                $instructor_id,
                $price,
                $discount_price,
                $duration,
                $imagePath,
                $videoPath,
                $status,
                $category_id,
                $subcategory_id
            );

            // Chuyển hướng về /courses
            header("Location: /courses");
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // GET: /admin/courses/edit/{id} -> Form edit
    public function editForm($id)
    {
        $course = $this->courseModel->getCourseById($id);
        renderViewAdmin("view/courses/edit.php", ["course" => $course], "Edit Course");
    }

    // POST: /admin/courses/update/{id} -> Xử lý update
    public function update($id)
    {
        try {
            // Lấy dữ liệu text
            $title          = $_POST['title']          ?? '';
            $description    = $_POST['description']    ?? '';
            $instructor_id  = $_POST['instructor_id']  ?? 0;
            $price          = $_POST['price']          ?? 0;
            $discount_price = $_POST['discount_price'] ?? 0;
            $duration       = $_POST['duration']       ?? 0;
            $status         = $_POST['status']         ?? 'inactive';
            $category_id    = $_POST['category_id']    ?? 0;
            $subcategory_id = $_POST['subcategory_id'] ?? 0;

            // Giữ image cũ
            $old_image = $_POST['old_image'] ?? '';
            // Nếu user upload file mới
            $imagePath = $old_image;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir  = "uploads/";
                $fileName   = time() . "_" . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $fileName;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                $imagePath  = $targetFile;
            }

            // Giữ video cũ
            $old_video = $_POST['old_video_intro'] ?? '';
            $videoPath = $old_video;
            if (isset($_FILES['video_intro']) && $_FILES['video_intro']['error'] === 0) {
                $targetDir  = "uploads/videos/";
                $fileName   = time() . "_" . basename($_FILES['video_intro']['name']);
                $targetFile = $targetDir . $fileName;
                move_uploaded_file($_FILES['video_intro']['tmp_name'], $targetFile);
                $videoPath  = $targetFile;
            } else {
                // Hoặc user nhập URL mới (thay cho file)
                if (!empty($_POST['video_intro'])) {
                    $videoPath = $_POST['video_intro'];
                }
            }

            // Gọi model update
            $this->courseModel->update(
                $id,
                $title,
                $description,
                $instructor_id,
                $price,
                $discount_price,
                $duration,
                $imagePath,
                $videoPath,
                $status,
                $category_id,
                $subcategory_id
            );

            header("Location: /courses");
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // GET/POST: /admin/courses/delete/{id} -> Xoá
    public function destroy($id)
    {
        $this->courseModel->delete($id);
        header("Location: /courses");
        exit;
    }
}
