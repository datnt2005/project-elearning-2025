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
    color: var(--text-light);
}

.btn-primary {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-primary:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    color: var(--text-light);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-btn);
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.text-primary {
    color: var(--header-bg) !important;
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Chỉnh sửa bài viết</h1>
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa bài viết</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" 
                       class="form-control mb-3 p-3 rounded-xl shadow-md" required>

                <select name="category" class="form-control mb-3 p-2 rounded-xl shadow-md" required>
                    <option value="">Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>" 
                                <?= ($post['category_id'] == $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Ảnh đại diện -->
                <div class="mb-3">
                    <label>Ảnh hiện tại:</label><br>
                    <?php if (!empty($post['thumbnail'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($post['thumbnail']) ?>" width="100"><br>
                    <?php else: ?>
                        <span class="text-muted">Không có ảnh</span><br>
                    <?php endif; ?>
                </div>
                <input type="file" name="thumbnail" class="form-control mb-3 p-2 rounded-xl shadow-md" accept="image/*">

                <!-- Nội dung bài viết -->
                <textarea name="content" class="form-control mb-3 p-3 rounded-xl shadow-md" required>
                    <?= htmlspecialchars($post['content']) ?>
                </textarea>

                <!-- Nút cập nhật -->
                <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
                <a href="/admin/post" class="btn btn-secondary mt-3">Hủy</a>
            </form>
        </div>
    </div>
</div>
