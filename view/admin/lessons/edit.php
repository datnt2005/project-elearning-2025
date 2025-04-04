<!-- file: view/lessons/edit.php -->
<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.card-header {
    background: var(--header-bg) !important;
    border-bottom: 1px solid var(--border-color);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-btn);
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.btn-success {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-success:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    transform: translateY(-1px);
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Chỉnh sửa bài học</h1>
    <div class="card shadow-sm">
        <div class="card-header text-white">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa bài học</h5>
        </div>
        <div class="card-body">
            <form action="/admin/lessons/update/<?= $lesson['id'] ?>" method="POST">
                <input type="hidden" name="id" value="<?= $lesson['id'] ?>">

                <div class="mb-3">
                    <label>Phần học (Section)</label>
                    <select name="section_id" class="form-control" required>
                        <option value="">-- Chọn phần học --</option>
                        <?php foreach ($sections as $sec): ?>
                            <option value="<?= $sec['id'] ?>"
                                <?= ($sec['id'] == $lesson['section_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sec['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tiêu đề bài học</label>
                    <input type="text" name="title" class="form-control" 
                           value="<?= htmlspecialchars($lesson['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($lesson['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Video URL</label>
                    <input type="text" name="video_url" class="form-control" 
                           value="<?= htmlspecialchars($lesson['video_url']) ?>">
                </div>

                <div class="mb-3">
                    <label>Nội dung (text/html)</label>
                    <textarea name="content" class="form-control"><?= htmlspecialchars($lesson['content']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Thứ tự sắp xếp (order_number)</label>
                    <input type="number" name="order_number" class="form-control" 
                           value="<?= htmlspecialchars($lesson['order_number']) ?>">
                </div>

                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>