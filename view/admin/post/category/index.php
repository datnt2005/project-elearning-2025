<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách danh mục bài viết</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Danh sách danh mục bài viết</h2>
    <?php if (isset($_SESSION["success_message"])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION["success_message"] ?>
        <?php unset($_SESSION["success_message"]); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION["error_message"])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION["error_message"] ?>
        <?php unset($_SESSION["error_message"]); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    <a href="postCategory/create" class="btn btn-primary mb-3">+ Thêm danh mục</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($postCategories)): ?>
                    <?php foreach ($postCategories as $postCategory): ?>
                        <tr>
                            <td><?= htmlspecialchars($postCategory['id']) ?></td>
                            <td><?= htmlspecialchars($postCategory['name']) ?></td>
                            <td class="text-center">
                                <a href="/admin/postCategory/edit/<?= $postCategory['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="/admin/postCategory/delete/<?= $postCategory['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Không có danh mục nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>
