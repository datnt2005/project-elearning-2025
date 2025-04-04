<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa danh mục con</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục chính</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                        <?= ($category['id'] == $subcategory['category_id']) ? 'selected' : '' ?>>
                                        <?= $category['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục con</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                value="<?= htmlspecialchars($subcategory['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" 
                                rows="3"><?= htmlspecialchars($subcategory['description']) ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-1"></i> Cập nhật
                            </button>
                            <a href="/admin/subcategories" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem var(--input-focus);
}

.btn-danger {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-danger:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
    transform: translateY(-1px);
}
</style>
