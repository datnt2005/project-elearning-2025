<?php
require_once "Database.php";

class notesModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả ghi chú
    public function getAllNotes() {
        $query = "SELECT * FROM notes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy ghi chú theo ID
    public function getNoteById($id) {
        $query = "SELECT * FROM notes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createNote($users_id, $courses_id, $lessons_id, $note, $video_time) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO notes (users_id, courses_id, lessons_id, note, video_time, created_at)
                VALUES (:users_id, :courses_id, :lessons_id, :note, :video_time, NOW())
            ");
            $stmt->bindParam(':users_id', $users_id, PDO::PARAM_INT);
            $stmt->bindParam(':courses_id', $courses_id, PDO::PARAM_INT);
            $stmt->bindParam(':lessons_id', $lessons_id, PDO::PARAM_INT);
            $stmt->bindParam(':note', $note, PDO::PARAM_STR);
            $stmt->bindParam(':video_time', $video_time, PDO::PARAM_STR);
    
            $result = $stmt->execute();
            return $result; // Trả về true nếu thành công, false nếu thất bại
        } catch (PDOException $e) {
            error_log("Lỗi trong createNote: " . $e->getMessage());
            return false;
        }
    }


    public function getNotesByCourse($users_id, $courses_id) {
        $query = "
            SELECT n.*, 
                   l.title AS lesson_name,
                   l.order_number AS lesson_order,
                   s.title AS section_name,
                   s.order_number AS section_order
            FROM notes n
            LEFT JOIN lessons l ON n.lessons_id = l.id
            LEFT JOIN sections s ON l.section_id = s.id
            WHERE n.users_id = :users_id AND n.courses_id = :courses_id 
            ORDER BY n.video_time ASC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':users_id', $users_id, PDO::PARAM_INT);
        $stmt->bindParam(':courses_id', $courses_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateNote($id, $note) {
        $query = "UPDATE notes SET note = :note WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function deleteNote($id) {
        $query = "DELETE FROM notes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
