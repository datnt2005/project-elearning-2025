<div class="container-fluid px-4">
    <h1 class="mt-4 text-dark text-center">Danh sách danh mục con</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #2c3e50;">
            <h5><i class="fas fa-tags me-2"></i>Quản lý danh mục con</h5>
            <a href="/subcategories/create" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm danh mục</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="" >
                    <tr>
                        <th>ID</th>
                        <th>Danh mục</th>
                        <th>Tên danh mục con</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subcategories as $subcategory): ?>
                        <tr>
                            <td><?= $subcategory['id'] ?></td>
                            <td><?= $subcategory['category_name'] ?></td>
                            <td><?= $subcategory['name'] ?></td>
                            <td><?= $subcategory['description'] ?></td>
                            <td>
                                <a href="/subcategories/<?= $subcategory['id'] ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="/subcategories/edit/<?= $subcategory['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/subcategories/delete/<?= $subcategory['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
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