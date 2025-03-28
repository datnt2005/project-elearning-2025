<?php
require_once __DIR__ . "/../model/SectionModel.php";
require_once __DIR__ . "/../model/CourseModel.php";
require_once __DIR__ . "/../view/helpers.php";

class SectionController
{
    private $sectionModel;
    private $courseModel;

    public function __construct()
    {
        $this->sectionModel = new Section();
        $this->courseModel  = new Course();
    }

    public function index()
    {
        $sections = $this->sectionModel->getAllSections();
        renderViewAdmin("view/admin/sections/list.php", ["sections" => $sections], "Section List");
    }

    public function createForm()
    {
        $courses = $this->courseModel->getAllCourses();
        renderViewAdmin("view/admin/sections/create.php", ["courses" => $courses], "Create Section");
    }

    public function store()
    {
        try {
            $course_id    = $_POST['course_id']    ?? 0;
            $title        = $_POST['title']        ?? '';
            $description  = $_POST['description']  ?? '';
            $order_number = $_POST['order_number'] ?? 0;

            $this->sectionModel->create($course_id, $title, $description, $order_number);
            header("Location: /admin/sections");
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function editForm($id)
    {
        $section = $this->sectionModel->getSectionById($id);
        $courses = $this->courseModel->getAllCourses();
        renderViewAdmin("view/admin/sections/edit.php", [
            "section" => $section,
            "courses" => $courses
        ], "Edit Section");
    }

    public function update($id)
    {
        try {
            $course_id    = $_POST['course_id']    ?? 0;
            $title        = $_POST['title']        ?? '';
            $description  = $_POST['description']  ?? '';
            $order_number = $_POST['order_number'] ?? 0;

            $this->sectionModel->update($id, $course_id, $title, $description, $order_number);
            header("Location: /admin/sections");
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function destroy($id)
    {
        $this->sectionModel->delete($id);
        header("Location: /admin/sections");
        exit;
    }
}
