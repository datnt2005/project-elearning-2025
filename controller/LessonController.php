<?php
require_once __DIR__ . "/../model/LessonModel.php";
require_once __DIR__ . "/../model/SectionModel.php";
require_once __DIR__ . "/../view/helpers.php";

class LessonController
{
    private $lessonModel;
    private $sectionModel;

    public function __construct()
    {
        $this->lessonModel  = new Lesson();
        $this->sectionModel = new Section();
    }

    public function index()
    {
        $lessons = $this->lessonModel->getAllLessons();

        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/lessons/list.php", ["lessons" => $lessons], "Lesson List");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/lessons/list.php", ["lessons" => $lessons], "Lesson List");
        }
    }

    public function createForm()
    {
        $sections = $this->sectionModel->getAllSections();

        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/lessons/create.php", ["sections" => $sections], "Create Lesson");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/lessons/create.php", ["sections" => $sections], "Create Lesson");
        }
    }
        public function store()
        {
            try {
                $section_id   = $_POST['section_id']   ?? 0;
                $title        = $_POST['title']        ?? '';
                $description  = $_POST['description']  ?? '';
                $video_url    = $_POST['video_url']    ?? '';
                $content      = $_POST['content']      ?? '';
                $order_number = $_POST['order_number'] ?? 0;

                $pdfPath = null;
                if (isset($_FILES['pdf_path']) && $_FILES['pdf_path']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['pdf_path']['tmp_name'];
                    $originalName = $_FILES['pdf_path']['name'];
                    $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                    $fileName = time() . '_' . basename($originalName);
                    $destination = __DIR__ . '/../uploads/files/' . $fileName;
                
                    if (move_uploaded_file($fileTmpPath, $destination)) {
                        $pdfPath = '/uploads/files/' . $fileName; // lưu vào DB khớp với file thực
                    }
                }
                
                

                $this->lessonModel->create($section_id, $title, $description, $video_url, $content, $order_number, $pdfPath);
                header("Location: /admin/lessons");
            } else if ($_SESSION['user']['role'] === 'instructor') {
                header("Location: /instructor/lessons");
            }
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function editForm($id)
    {
        $lesson   = $this->lessonModel->getLessonById($id);
        $sections = $this->sectionModel->getAllSections();

        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/lessons/edit.php", [
                "lesson"   => $lesson,
                "sections" => $sections
            ], "Edit Lesson");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/lessons/edit.php", [
                "lesson"   => $lesson,
                "sections" => $sections
            ], "Edit Lesson");
        }
    }

        public function update($id)
        {
            try {
                $section_id   = $_POST['section_id']   ?? 0;
                $title        = $_POST['title']        ?? '';
                $description  = $_POST['description']  ?? '';
                $video_url    = $_POST['video_url']    ?? '';
                $content      = $_POST['content']      ?? '';
                $order_number = $_POST['order_number'] ?? 0;
        
                // Giữ giá trị mặc định nếu không upload mới
                $pdfPath = $_POST['current_pdf_path'] ?? null;
        
                // Nếu có upload mới thì xử lý
                if (isset($_FILES['pdf_path']) && $_FILES['pdf_path']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['pdf_path']['tmp_name'];
                    $fileName = time() . '_' . basename($_FILES['pdf_path']['name']);
                    $destination = __DIR__ . '/../uploads/files/' . $fileName;
        
                    if (move_uploaded_file($fileTmpPath, $destination)) {
                        $pdfPath = '/uploads/files/' . $fileName;
                    } else {
                        throw new Exception("Không thể lưu file PDF.");
                    }
                }
        
                // Gọi model cập nhật
                $result = $this->lessonModel->update($id, $section_id, $title, $description, $video_url, $content, $order_number, $pdfPath);
        
                if ($result) {
                    // Chuyển hướng kèm thông báo
                    header("Location: /admin/lessons?success=1");
                } else {
                    throw new Exception("Không thể cập nhật bài học.");
                }
                exit;
            } catch (Exception $e) {
                // In ra lỗi nếu có
                echo "<div style='color:red;'>Lỗi: " . $e->getMessage() . "</div>";
            }
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function destroy($id)
    {
        $this->lessonModel->delete($id);

        if ($_SESSION['user']['role'] === 'admin') {
            header("Location: /admin/lessons");
        } else if ($_SESSION['user']['role'] === 'instructor') {
            header("Location: /instructor/lessons");
        }
        exit;
    }
}
