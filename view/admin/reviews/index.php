<style>
    .mt-4 {
        color: #69BA31;
    }
    .table-bordered th {
        color: #69BA31;
    }
    .btn-submit {
        background: white;
        color: #69BA31;
    }
    .btn-submit:hover {
        background: rgb(21, 210, 125);
        color: white;
    }
</style>
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Danh sách Đánh giá</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #69BA31;">
            <h5><i class="fas fa-star me-2"></i> Quản lý Đánh giá</h5>
            <a href="/admin/reviews/create" class="btn btn-submit"><i class="fas fa-plus"></i> Thêm Đánh giá</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khóa học</th>
                        <th>Người dùng</th>
                        <th>Đánh giá sao</th>
                        <th>Bình luận</th>
                        <th>Lượt thích</th>
                        <th>Ảnh</th>
                        <th>Phản hồi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?= $review['id'] ?></td>
                            <td><?= $review['title'] ?></td>
                            <td><?= $review['user_name'] ?></td>
                            <td><?= $review['rating'] ?></td>
                            <td><?= $review['comment'] ?></td>
                            <td><?= $review['like_count'] ?></td>
                            <td>
                                <?php if (!empty($review['images'])): ?>
                                    <?php foreach ($review['images'] as $image): ?>
                                        <img src="/<?= $image ?>" width="50" class="rounded">
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($review['replies'])): ?>
                                    <?php foreach ($review['replies'] as $reply): ?>
                                        <p><b><?= $reply['user_name'] ?>:</b> <?= $reply['comment'] ?> <br> <small>(<?= $reply['created_at'] ?>)</small></p>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/reviews/edit/<?= $review['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/admin/reviews/delete/<?= $review['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
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