<!-- file: view/sections/index.php -->
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Danh sách Section</h1>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" 
             style="background: #69BA31;">
            <h5><i class="fas fa-folder me-2"></i> Quản lý Section</h5>
            <a href="/sections/create" class="btn btn-submit">
                <i class="fas fa-plus"></i> Thêm Section
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khóa học</th>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Thứ tự</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sections as $section): ?>
                        <tr>
                            <td><?= $section['id'] ?></td>
                            <td><?= htmlspecialchars($section['course_title'] ?? '') ?></td>
                            <td><?= htmlspecialchars($section['title']) ?></td>
                            <td><?= htmlspecialchars(substr($section['description'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars($section['order_number']) ?></td>
                            <td>
                                <a href="/sections/edit/<?= $section['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/sections/delete/<?= $section['id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
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
