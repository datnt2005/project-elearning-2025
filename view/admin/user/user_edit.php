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

.btn-dark {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-dark:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    transform: translateY(-1px);
}
</style>

<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Chỉnh sửa người dùng</h5>
                </div>
                <div class="card-body">
                    <form action="/admin/user/edit/<?= $user['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên người dùng</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <?php if (!empty($user['image'])): ?>
                                <div class="mt-2">
                                    <img src="/uploads/<?= htmlspecialchars($user['image']) ?>" alt="Ảnh đại diện" width="100">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò</label>
                            <select class="form-control" id="role" name="role">
                                <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Học viên</option>
                                <option value="instructor" <?= $user['role'] == 'instructor' ? 'selected' : '' ?>>Giáo viên</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save me-1"></i> Cập nhật
                            </button>
                            <a href="/admin/user" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
