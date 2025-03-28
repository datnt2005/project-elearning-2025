<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách bài viết</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Danh sách bài viết</h2>

        <?php if (isset($_SESSION["success_message"])): ?>
            <div class="alert alert-success"><?= $_SESSION["success_message"] ?></div>
            <?php unset($_SESSION["success_message"]); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION["error_message"])): ?>
            <div class="alert alert-danger"><?= $_SESSION["error_message"] ?></div>
            <?php unset($_SESSION["error_message"]); ?>
        <?php endif; ?>

        <a href="/admin/post/create" class="btn btn-primary mb-3">+ Thêm bài viết</a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Ngày tạo</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?= htmlspecialchars($post['id']) ?></td>
                                <td>
                                    <?php if (!empty($post['thumbnail'])): ?>
                                        <img src="../uploads/<?= htmlspecialchars($post['thumbnail']) ?>" alt="Thumbnail"
                                            width="80">
                                    <?php else: ?>
                                        <span class="text-muted">Không có ảnh</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['name']) ?></td>
                                <td><?= date("d/m/Y H:i", strtotime($post['created_at'])) ?></td>
                                <td class="text-center">
                                    <a href="/admin/post/edit/<?= $post['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                    <a href="/admin/post/delete/<?= $post['id'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có bài viết nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>