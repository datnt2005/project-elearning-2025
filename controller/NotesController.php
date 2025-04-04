<?php
require_once "model/NotesModel.php";
require_once "view/helpers.php";

class NotesController {
    private $notesModel;

    public function __construct() {
        $this->notesModel = new NotesModel();  // Chú ý, class tên phải viết hoa
    }

    // Hiển thị danh sách ghi chú
    public function index() {
        $notes = $this->notesModel->getAllNotes();
        renderViewAdmin("view/notes/notes_list.php", compact('notes'), "Notes List");
    }

    // Hiển thị chi tiết ghi chú
    public function show($id) {
        $note = $this->notesModel->getNoteById($id);
        renderViewAdmin("view/notes/notes_detail.php", compact('note'), "Notes Detail");
    }

  public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        $users_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $courses_id = isset($_POST['courses_id']) ? $_POST['courses_id'] : null;
        $lessons_id = isset($_POST['lessons_id']) ? $_POST['lessons_id'] : null;
        $note = isset($_POST['note']) ? $_POST['note'] : null;
        $video_time = isset($_POST['video_time']) ? $_POST['video_time'] : null;

        // Debug: Ghi log dữ liệu nhận được
        error_log("POST data: " . print_r($_POST, true));

        if (!$users_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để tạo ghi chú.'
            ]);
            exit;
        }

        if (!$courses_id || !$lessons_id || !$note || !$video_time) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin cần thiết để tạo ghi chú. Dữ liệu: ' . json_encode($_POST)
            ]);
            exit;
        }

        try {
            $result = $this->notesModel->createNote($users_id, $courses_id, $lessons_id, $note, $video_time);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Ghi chú đã được tạo thành công!'
                ]);
            } else {
                // Debug: Ghi log lỗi từ model
                error_log("Lỗi khi lưu ghi chú: Không có kết quả từ createNote");
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Có lỗi xảy ra khi lưu ghi chú vào cơ sở dữ liệu.'
                ]);
            }
        } catch (Exception $e) {
            // Debug: Ghi log chi tiết lỗi
            error_log("Lỗi trong createNote: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi gửi ghi chú: ' . $e->getMessage()
            ]);
        }
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Phương thức không được hỗ trợ.'
        ]);
        exit;
    }
}
public function getNotesByCourse() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Content-Type: application/json');

        $users_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $courses_id = isset($_GET['courses_id']) ? $_GET['courses_id'] : null;

        if (!$users_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để xem ghi chú.'
            ]);
            exit;
        }

        if (!$courses_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu courses_id để lấy danh sách ghi chú.'
            ]);
            exit;
        }

        $notes = $this->notesModel->getNotesByCourse($users_id, $courses_id);

        if ($notes !== false) {
            echo json_encode([
                'status' => 'success',
                'notes' => $notes
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể lấy danh sách ghi chú.'
            ]);
        }
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Phương thức không được hỗ trợ.'
        ]);
        exit;
    }
}
public function edit($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        // Lấy dữ liệu thô từ POST, không mã hóa HTML
        $note = isset($_POST['note']) ? $_POST['note'] : null;

        if (!$note) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin cần thiết để cập nhật ghi chú.'
            ]);
            exit;
        }

        try {
            // Ghi log để kiểm tra dữ liệu trước khi lưu
            error_log("Dữ liệu trước khi lưu: " . $note);

            $result = $this->notesModel->updateNote($id, $note);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Ghi chú đã được cập nhật thành công!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Có lỗi xảy ra khi cập nhật ghi chú vào cơ sở dữ liệu.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Lỗi trong updateNote: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi gửi ghi chú: ' . $e->getMessage()
            ]);
        }
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Phương thức không được hỗ trợ.'
        ]);
        exit;
    }
}

public function delete($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        try {
            $result = $this->notesModel->deleteNote($id);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Ghi chú đã được xóa thành công!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Có lỗi xảy ra khi xóa ghi chú.'
                ]);
            }
        } catch (Exception $e) {
            error_log("Lỗi trong deleteNote: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xóa ghi chú: ' . $e->getMessage()
            ]);
        }
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Phương thức không được hỗ trợ.'
        ]);
        exit;
    }
}
}
    
?>
