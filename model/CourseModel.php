<?php
require_once "Database.php";

class Course
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả khoá học
    public function getAllCourses()
    {
        $query = "SELECT co.*, ca.name AS category_name, su.name AS subcategory_name FROM courses co
         LEFT JOIN categories ca ON co.category_id = ca.id
         LEFT JOIN subcategories su ON co.subcategory_id = su.id
         ORDER BY co.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 khoá học theo ID
    public function getCourseById($id)
    {
        $query = "SELECT co.*, sections.title AS section_title, lessons.title AS lesson_title,
         sections.id AS section_id, lessons.id AS lesson_id, sections.description AS section_description, lessons.description AS lesson_description,

         ca.name AS category_name, su.name AS subcategory_name FROM courses co
         LEFT JOIN categories ca ON co.category_id = ca.id
         LEFT JOIN subcategories su ON co.subcategory_id = su.id
         INNER JOIN sections ON co.id = sections.course_id
         INNER JOIN lessons ON sections.id = lessons.section_id

         WHERE co.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    // Tạo mới 1 khoá học
    // Các cột theo DB: title, description, instructor_id, price, discount_price, duration, image, video_intro, status, category_id, subcategory_id
    public function create($title, $description, $instructor_id, $price, $discount_price, $duration, $image, $video_intro, $status, $category_id, $subcategory_id)
    {
        // Validate cơ bản (VD: title không được rỗng, price >= 0, v.v.)
        if (empty($title)) {
            throw new Exception("Title is required.");
        }
        if ($price < 0) {
            throw new Exception("Price must be >= 0.");
        }

        $query = "INSERT INTO courses 
            (title, description, instructor_id, price, discount_price, duration, image, video_intro, status, category_id, subcategory_id) 
            VALUES 
            (:title, :description, :instructor_id, :price, :discount_price, :duration, :image, :video_intro, :status, :category_id, :subcategory_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':discount_price', $discount_price);
        $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':video_intro', $video_intro);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Update khoá học
    public function update($id, $title, $description, $instructor_id, $price, $discount_price, $duration, $image, $video_intro, $status, $category_id, $subcategory_id)
    {
        if (empty($title)) {
            throw new Exception("Title is required.");
        }
        if ($price < 0) {
            throw new Exception("Price must be >= 0.");
        }

        $query = "UPDATE courses SET
            title = :title,
            description = :description,
            instructor_id = :instructor_id,
            price = :price,
            discount_price = :discount_price,
            duration = :duration,
            image = :image,
            video_intro = :video_intro,
            status = :status,
            category_id = :category_id,
            subcategory_id = :subcategory_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':discount_price', $discount_price);
        $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':video_intro', $video_intro);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Xoá khoá học
    public function delete($id)
    {
        $query = "DELETE FROM courses WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
