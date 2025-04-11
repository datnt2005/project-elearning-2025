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

            $this->lessonModel->create($section_id, $title, $description, $video_url, $content, $order_number);

            if ($_SESSION['user']['role'] === 'admin') {
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

            $this->lessonModel->update($id, $section_id, $title, $description, $video_url, $content, $order_number);

            if ($_SESSION['user']['role'] === 'admin') {
                header("Location: /admin/lessons");
            } else if ($_SESSION['user']['role'] === 'instructor') {
                header("Location: /instructor/lessons");
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
