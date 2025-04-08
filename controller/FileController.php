<?php
class FileController {
    public function servePdf($fileName) {
        $filePath = dirname(__DIR__) . '/uploads/files/' . basename($fileName);

        if (file_exists($filePath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            readfile($filePath);
        } else {
            http_response_code(404);
            echo "❌ File không tồn tại tại: <br><strong>" . htmlspecialchars($filePath) . "</strong>";
        }
    }
}
