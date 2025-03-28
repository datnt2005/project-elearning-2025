<?php

// Dùng đường dẫn tuyệt đối (__DIR__) để tránh lỗi

use Google\Service\Adsense\Header;

require_once __DIR__ . "/../model/CourseModel.php";
require_once __DIR__ . "/../model/CategoryModel.php";
require_once __DIR__ . "/../model/SubcategoryModel.php";
require_once __DIR__ . "/../model/SectionModel.php";
require_once __DIR__ . "/../model/LessonModel.php";
require_once __DIR__ . "/../model/LessonProgressModel.php";

require_once __DIR__ . "/../view/helpers.php";

class CourseController
{
    private $courseModel;
    private $categoryModel;
    private $subcategoryModel;
    private $sectionModel;
    private $lessonModel;
    private $LessonProgressModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->categoryModel = new categoryModel();
        $this->subcategoryModel = new Subcategory();
        $this->sectionModel = new Section();
        $this->lessonModel = new Lesson();
        $this->LessonProgressModel = new LessonProgressModel();
    }

    public function home()
    {
        $courses = $this->courseModel->getAllCourses();
        renderViewUser("view/users/home.php", ["courses" => $courses], "Course List");
    }

    //show user
    public function show($id)
    {
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

    public function showCertificate(){
        if (!isset($_GET['certificate_url'])) {
            Header("Location: /404");
        }
    
        $certificate_url = $_GET['certificate_url'];
        $certificate = $this->LessonProgressModel->getCertificateByCode($certificate_url);
        if(!$certificate){
            Header("Location: /404");

        }
        // var_dump($certificate);
        renderViewUser("view/users/certificate.php", ["certificate" => $certificate], "Certificate");
    }
    public function detailCourse($id, $idLesson = null)
{
    $userId = $_SESSION['user']['id'] ?? null;

    if (!$userId) {
        header("Location: /login?error=Vui lòng đăng nhập để học khóa học!");
        exit;
    }

    $course = $this->courseModel->getCourseById($id);
    if (!$course) {
        header("Location: /404");
        exit;
    }

    $sections = $this->sectionModel->getSectionsByCourseId($id);
    $lessonsBySection = [];
    $lessonProgressById = [];
    foreach ($sections as $section) {
        $lessons = $this->lessonModel->getLessonsBySectionId($section['id']);
        $lessonsBySection[$section['id']] = $lessons;
        foreach ($lessons as $lesson) {
            $lessonProgressById[$lesson['id']] = $this->LessonProgressModel->getProgress($userId, $lesson['id']);
        }
    }

    if (!$idLesson) {
        foreach ($lessonsBySection as $lessons) {
            if (!empty($lessons)) {
                $idLesson = $lessons[0]['id'];
                break;
            }
        }
    }

    if (!$idLesson) {
        header("Location: /courses/learning/$id?error=Khóa học này chưa có bài học nào!");
        exit;
    }

    $lesson = $this->lessonModel->getLessonById($idLesson);
    if (!$lesson) {
        header("Location: /courses/learning/$id?error=Bài học không tồn tại!");
        exit;
    }

    // Lấy hoặc tạo enrollment (chỉ tạo nếu chưa tồn tại)
    $enrollment = $this->LessonProgressModel->getEnrollment($userId, $id);
    if (!$enrollment) {
        $initialProgress = $this->LessonProgressModel->calculateCourseProgress($userId, $id);
        $this->LessonProgressModel->updateEnrollment($userId, $id, date('Y-m-d H:i:s'), 'enrolled', $initialProgress);
        $enrollment = ['progress' => $initialProgress, 'status' => 'enrolled'];
    }

    // Kiểm tra khóa/mở bài học
    $lessons = $this->courseModel->getLessons($id);
    $prevLessonId = $this->getPreviousLessonId($lessons, $idLesson);
    $error = null;
    if ($prevLessonId) {
        $prevProgress = $this->LessonProgressModel->getProgress($userId, $prevLessonId);
        if (!$prevProgress['completed']) {
            $error = "Bạn cần hoàn thành bài học trước đó để tiếp tục!";
        }
    }

    // Kiểm tra chứng chỉ
    $certificate = $this->LessonProgressModel->checkCertificateCourse($userId, $id);

    renderViewUser("view/users/detailCourse.php", [
        "course" => $course,
        "sections" => $sections,
        "lessonsBySection" => $lessonsBySection,
        "currentLesson" => $lesson,
        "progress" => $enrollment,
        "lessonProgressById" => $lessonProgressById,
        "error" => $error,
        "lesson" => $lesson,
        "enrollment" => $enrollment,
        "certificate" => $certificate // Truyền thông tin chứng chỉ vào view
    ]);
}

    private function getPreviousLessonId($lessons, $currentLessonId)
    {
        foreach ($lessons as $index => $lesson) {
            if ($lesson['id'] == $currentLessonId) {
                if ($index > 0) {
                    return $lessons[$index - 1]['id'];
                }
                return null;
            }
        }
        return null;
    }

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
                // Admin upload file video
                $targetDir  = "uploads/";
                $fileName   = time() . "_" . basename($_FILES['video_intro']['name']);
                $targetFile = $targetDir . $fileName;
                move_uploaded_file($_FILES['video_intro']['tmp_name'], $targetFile);
                $videoPath  = $targetFile;
            } else {
                // Admin nhập URL thay vì upload
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


    public function updateProgress()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        $lessonId = $data['lesson_id'] ?? null;
        $progress = $data['progress'] ?? 0;
        $completed = $data['completed'] ?? false;

        // Kiểm tra dữ liệu đầu vào
        if (!$userId || !$lessonId || !isset($progress) || !isset($completed)) {
            file_put_contents('debug.log', "Missing required fields: " . print_r($data, true) . "\n", FILE_APPEND);
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            exit;
        }

        // Log dữ liệu nhận được
        file_put_contents('debug.log', "Received data: " . print_r($data, true) . "\n", FILE_APPEND);

        $result = $this->LessonProgressModel->updateProgress($userId, $lessonId, $progress, $completed);

        if ($result) {
            file_put_contents('debug.log', "Progress updated successfully for user_id: $userId, lesson_id: $lessonId\n", FILE_APPEND);
            echo json_encode(['status' => 'success']);
        } else {
            file_put_contents('debug.log', "Failed to update progress for user_id: $userId, lesson_id: $lessonId\n", FILE_APPEND);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update progress']);
        }
        exit;
    }

    public function calculateProgress()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'];
        $courseId = $data['course_id'];

        $courseProgress = $this->LessonProgressModel->calculateCourseProgress($userId, $courseId);

        echo json_encode(['status' => 'success', 'progress' => round($courseProgress)]);
    }

    public function updateEnrollment()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->LessonProgressModel->updateEnrollment($data['user_id'], $data['course_id'], $data['enrollment_date'], $data['status'], $data['progress']);

        $response = ['status' => $success ? 'success' : 'error', 'message' => $success ? 'Enrollment updated' : 'Failed'];

        // Nếu hoàn thành khóa học (progress = 100), cấp chứng chỉ
        if ($success && $data['progress'] >= 100) {
            $certificateResult = $this->LessonProgressModel->issueCertificate($data['user_id'], $data['course_id']);
            $response['certificate'] = $certificateResult;
        }

        echo json_encode($response);
    }
}
