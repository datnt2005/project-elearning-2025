<div class="container mt-4">
    <h2 class="text-center text-dark">Thêm Người Dùng</h2>

<br>
    <?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert-danger">
        <ul>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>


    <div class="card">
        <div class="card-body">
            <form action="/admin/user/create" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Tên người dùng</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh đại diện</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Vai trò</label>
                    <select class="form-control" id="role" name="role">
                        <option value="student">Học viên</option>
                        <option value="instructor">Giáo viên</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-control" id="status" name="status">
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Không hoạt động</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Thêm người dùng</button>
                <a href="/user" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>