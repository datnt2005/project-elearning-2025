<?php
class Post
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
            $sql = "SELECT id, name FROM post_categories";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy danh mục: " . $e->getMessage());
            return [];
        }
    }


    public function getAllPosts()
    {
        try {
            $sql = "SELECT posts.*, 
            users.name AS name, 
            post_categories.name AS category_name 
     FROM posts
     JOIN users ON posts.user_id = users.id
     JOIN post_categories ON posts.category_id = post_categories.id";

            $stmt = $this->conn->query($sql);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


            return $data;
        } catch (PDOException $e) {
            die("Lỗi truy vấn: " . $e->getMessage());  
        }
    }


    // Lấy bài viết theo ID
    public function getPostById($id)
    {
        try {
            $sql = "SELECT posts.*, users.name AS user_name FROM posts 
                    JOIN users ON posts.user_id = users.id 
                    WHERE posts.id = :id";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy bài viết: " . $e->getMessage());
            return false;
        }
    }

    // Thêm bài viết mới
    public function createPost($userId, $title, $category, $content, $thumbnail)
    {
        try {
            $sql = "INSERT INTO posts (user_id, title, category_id, content, thumbnail, created_at) 
                VALUES (:user_id, :title, :category_id, :content, :thumbnail, NOW())";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $category, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':thumbnail', $thumbnail, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Lỗi SQL: " . $errorInfo[2]);  // Ghi lỗi vào log server
                $_SESSION["error"] = "Lỗi SQL: " . $errorInfo[2]; // Hiển thị lỗi trên giao diện
                return false;
            }
        } catch (PDOException $e) {
            error_log("Lỗi PDO: " . $e->getMessage()); // Ghi lỗi vào log server
            $_SESSION["error"] = "Lỗi PDO: " . $e->getMessage(); // Hiển thị lỗi trên giao diện
            return false;
        }
    }

    // Cập nhật bài viết
    public function updatePost($id, $title, $category, $content, $thumbnail = null)
    {
        try {
            $sql = "UPDATE posts SET title = :title, category_id = :category, content = :content";
            if ($thumbnail) {
                $sql .= ", thumbnail = :thumbnail";
            }
            $sql .= " WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($thumbnail) {
                $stmt->bindParam(':thumbnail', $thumbnail, PDO::PARAM_STR);
            }
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi khi cập nhật bài viết: " . $e->getMessage());
            return false;
        }
    }
    // Xóa bài viết
    public function deletePost($id)
    {
        try {
            $sql = "DELETE FROM posts WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function getRelatedPosts($categoryId, $excludePostId, $limit = 5) {
        $query = "SELECT id, title 
                  FROM posts 
                  WHERE category_id = :category_id AND id != :post_id 
                  ORDER BY created_at DESC 
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':post_id', $excludePostId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
   
    
    
