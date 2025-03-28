<style>
    .mt-4 { color: #69BA31; }
    .table-bordered th { color: #69BA31; }
    .btn-submit { background: white; color: #69BA31; }
    .btn-submit:hover { background: rgb(21, 210, 125); color: white; }
</style>
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Danh sách khóa học</h1>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #69BA31;">
            <h5><i class="fas fa-book me-2"></i> Quản lý khóa học</h5>
            <a href="/admin/courses/create" class="btn btn-submit"><i class="fas fa-plus"></i> Thêm khóa học</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Giảng viên</th>
                        <th>Giá</th>
                        <th>Giá khuyến mãi</th>
                        <th>Thời lượng</th>
                        <th>Ảnh</th>
                        <th>Video giới thiệu</th>
                        <th>Danh mục</th>
                        <th>Danh mục con</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= $course['id'] ?></td>
                            <td><?= htmlspecialchars($course['title']) ?></td>
                            <td><?= htmlspecialchars(substr($course['description'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars($course['instructor_name'] ?? 'Chưa có') ?></td>
                            <td><?= number_format($course['price'], 0, ',', '.') ?> đ</td>
                            <td><?= $course['discount_price'] ? number_format($course['discount_price'], 0, ',', '.') . ' đ' : 'Không có' ?></td>
                            <td><?= htmlspecialchars($course['duration']) ?></td>
                            <td>
                                <?php if (!empty($course['image'])): ?>
                                    <img src="/<?= $course['image'] ?>" alt="Ảnh khóa học" width="50">
                                <?php else: ?>
                                    Không có
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($course['video_intro'])): ?>
                                    <a href="/<?= $course['video_intro'] ?>" target="_blank">Xem video</a>
                                <?php else: ?>
                                    Không có
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($course['category_name'] ?? 'Chưa có') ?></td>
                            <td><?= htmlspecialchars($course['subcategory_name'] ?? 'Chưa có') ?></td>
                            <td><?= $course['status'] ? '<span class="text-success">Hoạt động</span>' : '<span class="text-danger">Tạm dừng</span>' ?></td>
                            <td>
                                <a href="/admin/courses/edit/<?= $course['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Sửa</a>
                                <a href="/admin/courses/delete/<?= $course['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');"><i class="fas fa-trash"></i> Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
