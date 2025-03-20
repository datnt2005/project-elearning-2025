<div class="container-fluid px-4">
    <h1 class="mt-4 text-dark text-center">Danh sách Câu trả lời</h1>
    <!-- thông báo  -->
    <?php if (!empty($_SESSION['success_message'])):  ?>
    <p style="color: green;"><?= $_SESSION['success_message'] ?></p>
    <?php unset($_SESSION['success_message']); ?>

    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white"
            style="background: #2c3e50;">
            <h5><i class="fas fa-tags me-2"></i>Quản lý câu trả lời</h5>
            <a href="admin/quizAnswers/create" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm câu trả lời</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="">
                    <tr>
                        <th>ID</th>
                        <th>Câu hỏi</th>
                        <th>Câu trả lời</th>
                        <th>Kết quả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizAnswers as $quiz) : ?>
                        <tr class="answer-group">
                            <td><?= htmlspecialchars($quiz['question_id']) ?></td>
                            <td><?= htmlspecialchars($quiz['question']) ?></td>
                            <td><?= htmlspecialchars($quiz['answer']) ?></td>
                            <td>
                                <?php
                                    echo $quiz['is_correct'] == 1 ? "<span style='color:green;'>✅</span>" : "<span style='color:red;'>❌</span>";
                                ?>
                            </td>
                            <td>
                                <a href="#" class="btn btn-info">👁️ Xem</a>
                                <a href="/admin/quizAnswers/update/<?= $quiz['answer_id'] ?>" class="btn btn-warning">✏️ Sửa</a>
                                <a onclick="return confirm('Bạn muốn xoá câu trả lời nây?') " href="/admin/quizAnswers/delete/<?= $quiz['answer_id'] ?>" class="btn btn-danger delete-answer">🗑️ Xóa</a> <!-- Sử dụng answer_id -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>