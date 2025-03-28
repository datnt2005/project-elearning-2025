<?php
class PostCategory
{
    private $conn;

    public function __construct()
    {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();
        } catch (PDOException $e) {
            die("Lỗi kết nối Database: " . $e->getMessage());
        }
    }

    public function getAllCategories()
    {
        try {
            $sql = "SELECT * FROM post_categories";
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function getCategoryById($id)
    {
        if (!is_numeric($id) || intval($id) <= 0) {
            return ["error" => "Invalid ID"];
        }
        try {
            $sql = "SELECT * FROM post_categories WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: ["error" => "Category not found"];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }


    public function createCategory($name)
    {
        if (empty($name)) {
            return false;
        }
        try {
            $sql = "INSERT INTO post_categories (name) VALUES (:name)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi khi thêm danh mục: " . $e->getMessage());
            return false;
        }
    }


    public function updateCategory($id, $name)
    {
        if (empty($name) || !is_numeric($id) || intval($id) <= 0) {
            return false;
        }

        try {
            $sql = "UPDATE post_categories SET name = :name WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi khi cập nhật danh mục: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id)
{
    if (!is_numeric($id) || intval($id) <= 0) {
        return false;
    }

    try {
        $sql = "DELETE FROM post_categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Lỗi khi xóa danh mục: " . $e->getMessage());
        return false;
    }
}
}
