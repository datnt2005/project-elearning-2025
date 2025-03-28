<!-- file: view/lessons/index.php -->
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Danh sách bài học</h1>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" 
             style="background: #69BA31;">
            <h5><i class="fas fa-book me-2"></i> Quản lý bài học</h5>
            <a href="/admin/lessons/create" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Thêm bài học
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phần học (Section)</th>
                        <th>Tiêu đề bài học</th>
                        <th>Mô tả</th>
                        <th>Video URL</th>
                        <th>Nội dung</th>
                        <th>Thứ tự</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lessons as $lesson): ?>
                        <tr>
                            <td><?= $lesson['id'] ?></td>
                            <td><?= htmlspecialchars($lesson['section_title'] ?? '') ?></td>
                            <td><?= htmlspecialchars($lesson['title']) ?></td>
                            <td><?= htmlspecialchars(substr($lesson['description'], 0, 50)) ?>...</td>
                            <td>
                                <?php if (!empty($lesson['video_url'])): ?>
                                    <a href="<?= htmlspecialchars($lesson['video_url']) ?>" target="_blank">Xem video</a>
                                <?php else: ?>
                                    Không có
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(substr($lesson['content'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars($lesson['order_number']) ?></td>
                            <td>
                                <a href="/admin/lessons/edit/<?= $lesson['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/admin/lessons/delete/<?= $lesson['id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
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
