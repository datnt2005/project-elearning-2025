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
        $categories = $this->categoryModel->getAllCategory();
        
        renderViewAdmin("view/admin/subcategories/subcategories_list.php", compact('subcategories'), "subcategories List");
    }

    public function show($id) {
        $subcategories = $this->subcategoryModel->getSubcategoryById($id);
        renderViewAdmin("view/subcategories/subcategories_detail.php", compact('subcategories'), "subcategories Detail");
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->subcategoryModel->create($category_id, $name, $description);
            header("Location: /admin/subcategories");
        } else {
            $categories = $this->categoryModel->getAllCategory();
            renderViewAdmin("view/admin/subcategories/subcategories_create.php", compact('categories'), "subcategories Create");
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->subcategoryModel->update($id, $category_id, $name, $description);
            header("Location: /admin/subcategories");
        } else {
            $subcategory = $this->subcategoryModel->getSubcategoryById($id);
            $categories = $this->categoryModel->getAllCategory();
            renderViewAdmin("view/admin/subcategories/subcategories_edit.php", compact('subcategory', 'categories'), "subcategories Edit");
        }
    }

    public function delete($id) {
        $this->subcategoryModel->delete($id);
        header("Location: /admin/subcategories");
    }
}
