<style>
    .mt-4{
        color: #69BA31;
    }
    .table-bordered th {
        color: #69BA31;
    }
    .btn-submit{
        background: white;  
        color: #69BA31;
    }
    .btn-submit:hover{
        background:rgb(21, 210, 125);
        color: white;
    }
</style>
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Danh sách danh mục</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #69BA31;">
            <h5><i class="fas fa-tags me-2"></i>Quản lý danh mục</h5>
            <a href="categories/create" class="btn btn-submit"><i class="fas fa-plus"></i> Thêm danh mục</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="" >
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><?= $category['name'] ?></td>
                            <td><?= $category['description'] ?></td>
                            <td>
                                <a href="/admin/categories/<?= $category['id'] ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="/admin/categories/edit/<?= $category['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/admin/categories/delete/<?= $category['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
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