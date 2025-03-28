<div class="container p-4 mt-4">
    <h2 class="text-3xl font-bold mb-4 text-primary">Chỉnh sửa bài viết</h2>

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
