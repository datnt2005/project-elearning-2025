<div class="container mt-4">
    <h2 class="text-center text-dark">Chỉnh Sửa Người Dùng</h2>
    
    <div class="card">
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

                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="/admin/user" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
