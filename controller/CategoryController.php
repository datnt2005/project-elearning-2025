<?php
require_once "model/CategoryModel.php";
require_once "view/helpers.php";

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new categoryModel();
    }

    public function index() {
        $categories = $this->categoryModel->getAllcategory();
        //compact: gom bien dien thanh array
        renderViewAdmin("view/admin/categories/categories_list.php", compact('categories'), "categories List");
    }

    public function show($id) {
        $categories = $this->categoryModel->getcategoryById($id);
        renderViewAdmin("view/categories/categories_detail.php", compact('categories'), "categories Detail");
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->categoryModel->createcategory($name, $description);
            header("Location: /admin/categories");
        } else {
            renderViewAdmin("view/admin/categories/categories_create.php", [], "Create categories");
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];

            $this->categoryModel->updatecategory($id, $name, $description);
            header("Location: /categories");
        } else {
            $categories = $this->categoryModel->getcategoryById($id);
            renderViewAdmin("view/admin/categories/categories_edit.php", compact('categories'), "Edit categories");
        }
    }

    public function delete($id) {
        $this->categoryModel->deletecategory($id);
        header("Location: /categories");
    }
}