<?php
class FileController {
    public function servePdf($fileName) {
        $filePath = public_path('uploads/files/' . $fileName);
        if (file_exists($filePath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            readfile($filePath);
        } else {
            echo "File không tồn tại.";
        }
    }
}
