<?php
require_once "Database.php";

class Section
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả section (có JOIN với courses nếu muốn hiển thị thêm)
    public function getAllSections()
    {
        $query = "SELECT 
                    sections.*, 
                    courses.title AS course_title
                  FROM sections
                  LEFT JOIN courses ON sections.course_id = courses.id
                  ORDER BY sections.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 section
    public function getSectionById($id)
    {
        $query = "SELECT * FROM sections WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSectionsByCourseId($course_id)
    {
        $query = "SELECT * FROM sections WHERE course_id = :course_id ORDER BY order_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Tạo section
    // Cột: course_id, title, description, order_number
    public function create($course_id, $title, $description, $order_number)
    {
        if (empty($title)) {
            throw new Exception("Section title is required.");
        }

        $query = "INSERT INTO sections (course_id, title, description, order_number)
                  VALUES (:course_id, :title, :description, :order_number)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':order_number', $order_number, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Update section
    public function update($id, $course_id, $title, $description, $order_number)
    {
        if (empty($title)) {
            throw new Exception("Section title is required.");
        }

        $query = "UPDATE sections SET
                    course_id = :course_id,
                    title = :title,
                    description = :description,
                    order_number = :order_number
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':order_number', $order_number, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Xoá section
    public function delete($id)
    {
        $query = "DELETE FROM sections WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
