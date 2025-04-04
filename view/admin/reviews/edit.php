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

.btn-primary {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-primary:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    transform: translateY(-1px);
}
</style>
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Sửa Đánh giá</h1>
    <div class="card mb-4">
        <div class="card-header text-white" style="background: #69BA31;">
            <h5><i class="fas fa-star me-2"></i> Quản lý Đánh giá</h5>
        </div>
        <div class="card-body">
            <form action="/admin/reviews/update/<?= $review['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="course_id" class="form-label">Khóa học</label>
                    <select name="course_id" id="course_id" class="form-control" required>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= $review['course_id'] == $course['id'] ? 'selected' : '' ?>>
                                <?= $course['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label">Người dùng</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= $review['user_id'] == $user['id'] ? 'selected' : '' ?>>
                                <?= $user['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="rating" class="form-label">Đánh giá sao</label>
                    <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" value="<?= $review['rating'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Bình luận</label>
                    <textarea name="comment" id="comment" class="form-control" required><?= $review['comment'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="images" class="form-label">Ảnh đánh giá</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                    <?php if (!empty($review['images'])): ?>
                        <div class="mt-2">
                            <?php foreach ($review['images'] as $image): ?>
                                <img src="/<?= $image ?>" width="50" class="rounded">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="admin_id" class="form-label">Người phản hồi (Admin)</label>
                    <select name="admin_id" id="admin_id" class="form-control">
                        <option value="">Chọn Admin</option>
                        <?php foreach ($admins as $admin): ?>
                            <option value="<?= $admin['id'] ?>" <?= isset($reviewReply['user_id']) && $reviewReply['user_id'] == $admin['id'] ? 'selected' : '' ?>>
                                <?= $admin['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="admin_reply" class="form-label">Phản hồi của Admin</label>
                    <textarea name="admin_reply" id="admin_reply" class="form-control"><?= $reviewReply['comment'] ?? '' ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/admin/reviews" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
