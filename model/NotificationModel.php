<?php
require_once "Database.php";

class NotificationModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Tạo thông báo mới
    public function createNotification($userId, $message, $link = null, $adminId = null)
    {
        $query = "INSERT INTO notifications (user_id, message, link, admin_id) 
              VALUES (:user_id, :message, :link, :admin_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':admin_id', $adminId);
        return $stmt->execute();
    }

    public function createAutoNotification($userId, $message, $link = null, $adminId = null)
    {
        $sendStatus = 'sent'; // hoặc để biến mặc định
        $query = "INSERT INTO notifications (user_id, message, link, admin_id, send_status) 
              VALUES (:user_id, :message, :link, :admin_id, :send_status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':admin_id', $adminId);
        $stmt->bindParam(':send_status', $sendStatus);
        return $stmt->execute();
    }



    // Lấy tất cả thông báo (admin view)
    public function getAllNotifications()
    {
        $query = "SELECT n.*, u.name AS user_name 
                  FROM notifications n 
                  LEFT JOIN users u ON n.user_id = u.id 
                  ORDER BY n.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông báo theo ID
    public function getNotificationById($id)
    {
        $query = "SELECT * FROM notifications WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notificationId)
    {
        $query = "UPDATE notifications SET status = 'read' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $notificationId);
        return $stmt->execute();
    }

    // Lấy thông báo theo người dùng
    public function getNotificationsByUser($userId)
    {
        $query = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đánh dấu đã đọc

    // Cập nhật thông báo
    public function updateNotification($id, $userId, $message, $link = null, $adminId = null)
    {
        $query = "UPDATE notifications 
              SET user_id = :user_id, message = :message, link = :link, admin_id = :admin_id 
              WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':admin_id', $adminId);
        return $stmt->execute();
    }


    // Cập nhật trạng thái gửi
    public function updateSendStatus($id, $status)
    {
        $sql = "UPDATE notifications SET send_status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Xóa thông báo
    public function deleteNotification($notificationId)
    {
        $query = "DELETE FROM notifications WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $notificationId);
        return $stmt->execute();
    }

    // Lấy tất cả thông báo theo ID
    public function getNotificationsByIds($ids)
    {
        if (empty($ids)) return [];

        // Tạo chuỗi dấu ? tương ứng với số lượng id
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM notifications WHERE id IN ($placeholders)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserNotifications($userId)
    {
        $sql = "SELECT n.*, u.name AS admin_name, u.image AS admin_avatar
            FROM notifications n
            LEFT JOIN users u ON n.admin_id = u.id AND u.role = 'admin'
            WHERE (n.user_id = :userId OR n.user_id = 'all') 
            AND n.send_status = 'sent'
            ORDER BY n.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }


    public function countUnreadNotifications($userId)
    {
        $sql = "SELECT COUNT(*) FROM notifications 
            WHERE (user_id = :userId OR user_id = 'all') 
            AND status = 'unread' 
            AND send_status = 'sent'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchColumn();
    }
}
