<?php
require_once "model/NotificationModel.php";
require_once "model/UserModel.php";
require_once "view/helpers.php";

class NotificationController
{
    private $notificationModel;
    private $userModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
    }

    // Gửi thông báo hàng loạt
    public function sendNotification($userIds = null, $message, $link = null)
    {
        if ($userIds === null) {
            $users = $this->userModel->getAllUsers();
            $userIds = array_column($users, 'id'); // lấy mảng ID
        }

        foreach ($userIds as $userId) {
            $this->notificationModel->createNotification($userId, $message, $link);
        }
    }

    public function sendAutoNotification($userIds = null, $message, $link = null)
    {
        if ($userIds === null) {
            $users = $this->userModel->getAllUsers();
            $userIds = array_column($users, 'id'); // lấy mảng ID
        }

        foreach ($userIds as $userId) {
            $this->notificationModel->createAutoNotification($userId, $message, $link);
        }
    }

    // Danh sách thông báo
    public function index()
    {
        $notifications = $this->notificationModel->getAllNotifications();
        $users = $this->userModel->getAllUsers();
        renderViewAdmin("view/admin/notification/index.php", compact('notifications', 'users'), "Tất cả thông báo");
    }

    // Hiển thị form tạo mới
    public function create()
    {
        $allUsers = $this->userModel->getAllUserRole();
        $users = array_filter($allUsers, function ($user) {
            return $user['role'] !== 'admin'; // hoặc dùng role_id nếu bạn dùng số
        });
        renderViewAdmin("view/admin/notification/create.php", compact('users'), "Thêm thông báo");
    }

    // Xử lý thêm mới
    public function store()
    {
        $userId = $_POST['user_id'] ?? null;
        $message = $_POST['message'] ?? '';
        $link = $_POST['link'] ?? null;
        $adminId = $_SESSION['user']['id'];

        if (empty($userId) || empty($message)) {
            $_SESSION['error_message'] = "Vui lòng chọn người nhận và nhập nội dung!";
            header("Location: /admin/notifications/create");
            return;
        }

        if ($userId === 'all') {
            // Lấy tất cả user trừ admin
            $allUsers = $this->userModel->getAllUsers();
            $nonAdminUsers = array_filter($allUsers, function ($user) {
                return $user['role'] !== 'admin'; // hoặc $user['role_id'] != 1 nếu dùng role dạng số
            });

            foreach ($nonAdminUsers as $user) {
                $this->notificationModel->createNotification($user['id'], $message, $link, $adminId);
            }
        } else {
            $this->notificationModel->createNotification($userId, $message, $link, $adminId);
        }

        $_SESSION['success_message'] = "Đã tạo thông báo thành công!";
        header("Location: /admin/notifications");
    }



    // Hiển thị form sửa
    public function edit($id)
    {
        $notification = $this->notificationModel->getNotificationById($id);
        $users = $this->userModel->getAllUsers();
        renderViewAdmin("view/admin/notification/edit.php", compact('notification', 'users'), "Chỉnh sửa thông báo");
    }

    // Xử lý cập nhật
    public function update($id)
    {
        $userId = $_POST['user_id'] ?? null;
        $message = $_POST['message'] ?? '';
        $link = $_POST['link'] ?? null;
        $adminId = $_SESSION['user']['id'];

        if ($userId && $message) {
            $this->notificationModel->updateNotification($id, $userId, $message, $link, $adminId);
            $_SESSION['success_message'] = "Cập nhật thông báo thành công!";
        } else {
            $_SESSION['error_message'] = "Vui lòng nhập đầy đủ thông tin!";
        }

        header("Location: /admin/notifications");
    }

    // Xóa
    public function delete($id)
    {
        $this->notificationModel->deleteNotification($id);
        $_SESSION['success_message'] = "Xóa thông báo thành công!";
        header("Location: /admin/notifications");
    }

    // Đánh dấu đã đọc
    public function markAsRead($id)
    {
        $this->notificationModel->markAsRead($id);
        header("Location: /admin/notifications");
    }

    public function read($id)
    {
        $notification = $this->notificationModel->getNotificationById($id);

        // Chỉ đánh dấu đã đọc nếu là thông báo của user hiện tại
        if ($notification && $_SESSION['user']['id'] == $notification['user_id']) {
            $this->notificationModel->markAsRead($id);
        }

        // Chuyển hướng đến link nếu có, ngược lại quay lại home
        if (!empty($notification['link'])) {
            header("Location: " . $notification['link']);
        } else {
            header("Location: /home");
        }
        exit;
    }


    public function sendSelected()
    {
        $ids = $_POST['selected_ids'] ?? '';

        if (empty($ids)) {
            $_SESSION['error_message'] = "Không có thông báo nào được chọn.";
            header("Location: /admin/notifications");
            return;
        }

        $selectedIds = explode(',', $ids);
        $notifications = $this->notificationModel->getNotificationsByIds($selectedIds);

        foreach ($notifications as $noti) {
            $this->notificationModel->updateSendStatus($noti['id'], 'sent');
        }

        $_SESSION['success_message'] = "Đã gửi lại các thông báo thành công!";
        header("Location: /admin/notifications");
    }


    public function deleteSelected()
    {
        $ids = $_POST['selected_ids'] ?? '';

        if (empty($ids)) {
            $_SESSION['error_message'] = "Không có thông báo nào được chọn.";
            header("Location: /admin/notifications");
            return;
        }

        $selectedIds = explode(',', $ids);

        foreach ($selectedIds as $id) {
            $this->notificationModel->deleteNotification($id);
        }

        $_SESSION['success_message'] = "Đã xóa các thông báo đã chọn!";
        header("Location: /admin/notifications");
    }

    public function getAllNotifications()
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $notifications = [];
        $unreadCount = 0;

        if ($userId) {
            $notifications = $this->notificationModel->getUserNotifications($userId);
            $unreadCount = $this->notificationModel->countUnreadNotifications($userId);
        }

        renderViewUser("View/users/home.php", compact('notifications', 'unreadCount'), "Trang chủ người dùng");
    }
}
