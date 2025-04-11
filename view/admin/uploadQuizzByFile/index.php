<div class="container-fluid px-4">
    <h1 class="mt-4 text-dark text-center">Tất cả các file câu hỏi và câu trả lời</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #2c3e50;">
            <h5><i class="fas fa-tags me-2"></i>Quản lý File câu hỏi và câu trả lời</h5>
            <a href="/admin/uploadQuizzByFile/create" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm File</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="" >
                    <tr>
                        <th>ID</th>
                        <th>File_name</th>
                        <th>File_path</th>
                        <th>File_type</th>
                        <th>Quiz_id</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uploadedFiles as $upload): ?>
                        <tr>
                            <td><?= $upload['id'] ?></td>
                            <td><?= $upload['file_name'] ?></td>
                            <td><?= $upload['file_path'] ?></td>
                            <td><?= $upload['file_type'] ?></td>
                            <td><?= $upload['quiz_id'] ?></td>
                            <td>
                                <a href="<?= $upload['id'] ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="edit/<?= $upload['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="delete/<?= $upload['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>