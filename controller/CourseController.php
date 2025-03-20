<?php

// Dùng đường dẫn tuyệt đối (__DIR__) để tránh lỗi
require_once __DIR__ . "/../model/CourseModel.php";
require_once __DIR__ . "/../model/CategoryModel.php";
require_once __DIR__ . "/../model/SubcategoryModel.php";
require_once __DIR__ . "/../model/SectionModel.php";
require_once __DIR__ . "/../model/LessonModel.php";

require_once __DIR__ . "/../view/helpers.php";

class CourseController
{
    private $courseModel;
    private $categoryModel;
    private $subcategoryModel;
    private $sectionModel;
    private $lessonModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->categoryModel = new categoryModel();
        $this->subcategoryModel = new Subcategory();
        $this->sectionModel = new Section();
        $this->lessonModel = new Lesson();
    }

    public function home(){
        $courses = $this->courseModel->getAllCourses();
        renderViewUser("view/users/home.php", ["courses" => $courses], "Course List");
    }

    //show user
    public function show($id) {
        $course = $this->courseModel->getCourseById($id);
        $sections = $this->sectionModel->getSectionsByCourseId($id);
    
        $lessonsBySection = [];
        foreach ($sections as $section) {
            $lessonsBySection[$section['id']] = $this->lessonModel->getLessonsBySectionId($section['id']);
        }
    
        renderViewUser("view/users/courses.php", [
            "course" => $course, 
            "sections" => $sections, 
            "lessonsBySection" => $lessonsBySection
        ], "Course Detail");
    }
    
    // GET: /admin/courses -> Danh sách
    public function index()
    {
        $courses = $this->courseModel->getAllCourses();
        // Hiển thị danh sách ở view/admin/courses/list.php
        renderViewAdmin("view/admin/courses/list.php", ["courses" => $courses], "Course List");
    }

    // GET: /admin/courses/create -> Form tạo course
    public function createForm()
    {
        $categories = $this->categoryModel->getAllCategory();
        $subcategories = $this->subcategoryModel->getAllSubcategory();
        renderViewAdmin("view/admin/courses/create.php", ["categories" => $categories, "subcategories" => $subcategories], "Create Course");
    }

    // POST: /admin/courses/store -> Xử lý lưu course
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu text
            $title          = $_POST['title'];
            $description    = $_POST['description'];
            $instructor_id  = $_SESSION['user']['id'];
            $price          = $_POST['price']          ?? 0;
            $discount_price = $_POST['discount_price'] ?? 0;
            $duration       = $_POST['duration']       ?? 0;
            $status         = $_POST['status'];
            $category_id    = $_POST['category_id'];
            $subcategory_id = $_POST['subcategory_id'];

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
                $targetDir  = "uploads/";
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
            header("Location: /admin/courses");
            exit;
        }
    }

    // GET: /admin/courses/edit/{id} -> Form edit
    public function editForm($id)
    {
        $categories = $this->categoryModel->getAllCategory();
        $subcategories = $this->subcategoryModel->getAllSubcategory();
        $course = $this->courseModel->getCourseById($id);
        renderViewAdmin("view/admin/courses/edit.php", ["course" => $course, "categories" => $categories, "subcategories" => $subcategories],  "Edit Course");
    }

    // POST: /admin/courses/update/{id} -> Xử lý update
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title          = $_POST['title'];
            $description    = $_POST['description'];
            $instructor_id  = $_SESSION['user']['id'];
            $price          = $_POST['price'] ?? 0;
            $discount_price = $_POST['discount_price'] ?? 0;
            $duration       = $_POST['duration'] ?? 0;
            $status         = $_POST['status'];
            $category_id    = $_POST['category_id'];
            $subcategory_id = $_POST['subcategory_id'];

            // Lấy thông tin hiện tại của course
            $currentCourse = $this->courseModel->getCourseById($id);

            // Xử lý hình ảnh
            $imagePath = $currentCourse['image']; // Giữ nguyên hình ảnh cũ
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir  = "uploads/";
                $fileName   = time() . "_" . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $fileName;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                $imagePath  = $targetFile; // Cập nhật hình ảnh mới
            }

            // Xử lý video
            $videoPath = $currentCourse['video_intro']; // Giữ nguyên video cũ
            if (isset($_FILES['video_intro']) && $_FILES['video_intro']['error'] === 0) {
                $targetDir  = "uploads/videos/";
                $fileName   = time() . "_" . basename($_FILES['video_intro']['name']);
                $targetFile = $targetDir . $fileName;
                move_uploaded_file($_FILES['video_intro']['tmp_name'], $targetFile);
                $videoPath  = $targetFile; // Cập nhật video mới
            } else if (!empty($_POST['video_intro'])) {
                $videoPath = $_POST['video_intro']; // Cập nhật URL video mới
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
            header("Location: /admin/courses");
            exit;
        }
    }

    public function destroy($id)
    {
        $this->courseModel->delete($id);
        header("Location: /admin/courses");
        exit;
    }
}
