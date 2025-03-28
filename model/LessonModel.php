<?php
require_once "Database.php";

class Lesson
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả lesson
    public function getAllLessons()
    {
        $query = "SELECT
                    lessons.*,
                    sections.title AS section_title
                  FROM lessons
                  LEFT JOIN sections ON lessons.section_id = sections.id
                  ORDER BY lessons.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 lesson
    public function getLessonById($id)
    {
        $query = "SELECT * FROM lessons WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLessonsBySectionId($section_id)
    {
        $query = "SELECT * FROM lessons WHERE section_id = :section_id ORDER BY order_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Tạo lesson
    // Cột: section_id, title, description, video_url, content, order_number
    public function create($section_id, $title, $description, $video_url, $content, $order_number)
    {
        if (empty($title)) {
            throw new Exception("Lesson title is required.");
        }

        $query = "INSERT INTO lessons 
                    (section_id, title, description, video_url, content, order_number)
                  VALUES 
                    (:section_id, :title, :description, :video_url, :content, :order_number)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':video_url', $video_url);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':order_number', $order_number, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Update lesson
    public function update($id, $section_id, $title, $description, $video_url, $content, $order_number)
    {
        if (empty($title)) {
            throw new Exception("Lesson title is required.");
        }

        $query = "UPDATE lessons SET
                    section_id = :section_id,
                    title = :title,
                    description = :description,
                    video_url = :video_url,
                    content = :content,
                    order_number = :order_number
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':video_url', $video_url);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':order_number', $order_number, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Xoá lesson
    public function delete($id)
    {
        $query = "DELETE FROM lessons WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } 
    // Đánh dấu bài học hoàn thành
    public function markLessonComplete($userId, $courseId, $lessonId)
    {
        $query = "INSERT INTO user_progress (user_id, course_id, lesson_id, is_completed, completed_at)
                  VALUES (:userId, :courseId, :lessonId, 1, NOW())
                  ON DUPLICATE KEY UPDATE is_completed = 1, completed_at = NOW()";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':lessonId', $lessonId, PDO::PARAM_INT);
        $stmt->execute();
    }

       // Lấy số lượng bài học đã hoàn thành
       public function getCompletedLessonsCount($userId, $courseId)
       {
           $query = "SELECT COUNT(*) AS completed_lessons
                     FROM user_progress
                     WHERE user_id = :userId AND course_id = :courseId AND is_completed = 1";
   
           $stmt = $this->conn->prepare($query);
           $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
           $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
           $stmt->execute();
           return $stmt->fetch(PDO::FETCH_ASSOC)['completed_lessons'];
       }
 // Lấy tổng số bài học trong khóa học
 public function getTotalLessonsCount($courseId)
 {
     $query = "SELECT COUNT(*) AS total_lessons
               FROM lessons
               WHERE course_id = :courseId";

     $stmt = $this->conn->prepare($query);
     $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
     $stmt->execute();
     return $stmt->fetch(PDO::FETCH_ASSOC)['total_lessons'];
 }

 

}
