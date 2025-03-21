<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-dark text-center">Danh sách người dùng</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #2c3e50;">
            <h5><i class="fas fa-tags me-2"></i>Quản lý người dùng</h5>
            <a href="/admin/user/create" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm người dùng</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Trạng Thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['status']) ?></td>
                            <td>
                               <a href="/admin/user/edit/<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                                     <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/admin/user/delete/<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
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
