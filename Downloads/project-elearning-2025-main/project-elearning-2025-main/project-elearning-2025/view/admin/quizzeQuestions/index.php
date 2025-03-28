
<div class="container-fluid px-4">
    <h1 class="mt-4 text-dark text-center">Danh sách Câu hỏi</h1>
      <!-- thông báo  -->
      <?php if (!empty($_SESSION['success_message'])):  ?>
                <p style="color: green;"><?= $_SESSION['success_message'] ?></p>
      <?php unset($_SESSION['success_message']); ?>

      <?php endif; ?>

    <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center text-white"
            style="background: #2c3e50;">
            <h5><i class="fas fa-tags me-2"></i>Quản lý câu hỏi</h5>
            <a href="/admin/quizQuests/create" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm câu hỏi</a>
    </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="">
                    <tr>
                        <th>ID</th>
                        <th>Bài kiểm tra</th>
                        <th>Câu hỏi</th>
                        <th>Kiểu câu hỏi</th>
                       
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizQuestions as $quiz): ?>
                    <tr>
                        <td><?=  $quiz['id'] ?></td>
                        <td><?= htmlspecialchars($quiz['quiz_id']) ?></td>
                        <td><?= htmlspecialchars($quiz['question']) ?></td>
                        <td><?= htmlspecialchars($quiz['type']) ?></td>
                       
                        
                        <td>
                            <a href="#" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <a href="/admin/quizQuests/update/<?= $quiz['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="/admin/quizQuests/delete/<?= $quiz['id'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa khóa học này?');">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>

</div>