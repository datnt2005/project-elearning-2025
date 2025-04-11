<?php
require_once "model/SubcategoryModel.php";
require_once "model/CategoryModel.php";
require_once "view/helpers.php";

class SubcategoryController {
    private $subcategoryModel;
    private $categoryModel;

    public function __construct() {
        $this->subcategoryModel = new Subcategory();
        $this->categoryModel = new CategoryModel();
    }

    public function index() {
        $subcategories = $this->subcategoryModel->getAllSubcategory();

        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/subcategories/subcategories_list.php", compact('subcategories'), "Subcategories List");
        } elseif ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/subcategories/subcategories_list.php", compact('subcategories'), "Subcategories List");
        }
    }

    public function show($id) {
        $subcategory = $this->subcategoryModel->getSubcategoryById($id);

        if ($_SESSION['user']['role'] === 'admin') {
            renderViewAdmin("view/admin/subcategories/subcategories_detail.php", compact('subcategory'), "Subcategory Detail");
        } elseif ($_SESSION['user']['role'] === 'instructor') {
            renderViewInstructor("view/instructor/subcategories/subcategories_detail.php", compact('subcategory'), "Subcategory Detail");
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->subcategoryModel->create($category_id, $name, $description);

            $redirectPath = $_SESSION['user']['role'] === 'instructor' ? "/instructor/subcategories" : "/admin/subcategories";
            header("Location: $redirectPath");
        } else {
            $categories = $this->categoryModel->getAllCategory();

            if ($_SESSION['user']['role'] === 'admin') {
                renderViewAdmin("view/admin/subcategories/subcategories_create.php", compact('categories'), "Create Subcategory");
            } elseif ($_SESSION['user']['role'] === 'instructor') {
                renderViewInstructor("view/instructor/subcategories/subcategories_create.php", compact('categories'), "Create Subcategory");
            }
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->subcategoryModel->update($id, $category_id, $name, $description);

            $redirectPath = $_SESSION['user']['role'] === 'instructor' ? "/instructor/subcategories" : "/admin/subcategories";
            header("Location: $redirectPath");
        } else {
            $subcategory = $this->subcategoryModel->getSubcategoryById($id);
            $categories = $this->categoryModel->getAllCategory();

            if ($_SESSION['user']['role'] === 'admin') {
                renderViewAdmin("view/admin/subcategories/subcategories_edit.php", compact('subcategory', 'categories'), "Edit Subcategory");
            } elseif ($_SESSION['user']['role'] === 'instructor') {
                renderViewInstructor("view/instructor/subcategories/subcategories_edit.php", compact('subcategory', 'categories'), "Edit Subcategory");
            }
        }
    }

    public function delete($id) {
        $this->subcategoryModel->delete($id);

        $redirectPath = $_SESSION['user']['role'] === 'instructor' ? "/instructor/subcategories" : "/admin/subcategories";
        header("Location: $redirectPath");
    }
}
