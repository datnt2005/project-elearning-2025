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
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Thêm Section</h1>
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm Section mới</h5>
        </div>
        <div class="card-body">
            <!-- Hiển thị lỗi nếu có -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/admin/sections/store" method="POST">
                <div class="mb-3">
                    <label for="course_id" class="form-label">Khóa học</label>
                    <select id="course_id" name="course_id" class="form-control" required>
                        <option value="">-- Chọn khóa học --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>"
                                <?= (isset($old_data['course_id']) && $old_data['course_id'] == $course['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề Section</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tiêu đề section" required
                           value="<?= isset($old_data['title']) ? htmlspecialchars($old_data['title']) : '' ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả"><?= isset($old_data['description']) ? htmlspecialchars($old_data['description']) : '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="order_number" class="form-label">Thứ tự (order_number)</label>
                    <input type="number" id="order_number" name="order_number" class="form-control" value="<?= isset($old_data['order_number']) ? htmlspecialchars($old_data['order_number']) : '0' ?>" min="0" required>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-add">
                        <i class="fas fa-save me-1"></i> Thêm Section
                    </button>
                    <a href="/admin/sections" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
