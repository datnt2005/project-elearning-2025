<?php
require_once "model/CategoryModel.php";
require_once "view/helpers.php";

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new categoryModel();
    }

    private function renderByRole($viewAdmin, $viewInstructor, $data, $title) {
        $role = $_SESSION['user']['role'] ?? '';
        if ($role === 'instructor') {
            renderViewInstructor($viewInstructor, $data, $title);
        } else {
            renderViewAdmin($viewAdmin, $data, $title);
        }
    }

    public function index() {
        $categories = $this->categoryModel->getAllcategory();
        $data = compact('categories');
        $this->renderByRole(
            "view/admin/categories/categories_list.php",
            "view/instructor/categories/categories_list.php",
            $data,
            "Categories List"
        );
    }

    public function show($id) {
        $categories = $this->categoryModel->getcategoryById($id);
        $data = compact('categories');
        $this->renderByRole(
            "view/admin/categories/categories_detail.php",
            "view/instructor/categories/categories_detail.php",
            $data,
            "Category Detail"
        );
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->categoryModel->createcategory($name, $description);
            header("Location: /admin/categories");
        } else {
            $this->renderByRole(
                "view/admin/categories/categories_create.php",
                "view/instructor/categories/categories_create.php",
                [],
                "Create Category"
            );
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->categoryModel->updatecategory($id, $name, $description);
            header("Location: /admin/categories");
        } else {
            $categories = $this->categoryModel->getcategoryById($id);
            $data = compact('categories');
            $this->renderByRole(
                "view/admin/categories/categories_edit.php",
                "view/instructor/categories/categories_edit.php",
                $data,
                "Edit Category"
            );
        }
    }

    public function delete($id) {
        $this->categoryModel->deletecategory($id);
        header("Location: /admin/categories");
    }
}
