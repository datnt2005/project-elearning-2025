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
    <h1 class="mt-4 text-center">Danh sách Coupon</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #69BA31;">
            <h5><i class="fas fa-ticket-alt me-2"></i>Quản lý Coupon</h5>
            <a href="/admin/coupons/create" class="btn btn-submit"><i class="fas fa-plus"></i> Thêm Coupon</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Mô tả</th>
                        <th>Phần trăm giảm giá</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $coupon): ?>
                        <tr>
                            <td><?= $coupon['id'] ?></td>
                            <td><?= $coupon['code'] ?></td>
                            <td><?= $coupon['description'] ?></td>
                            <td><?= $coupon['discount_percent'] ?>%</td>
                            <td><?= $coupon['start_date'] ?></td>
                            <td><?= $coupon['end_date'] ?></td>
                            <td><?= $coupon['status'] ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <a href="admin/coupons/<?= $coupon['id'] ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="admin/coupons/edit/<?= $coupon['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="admin/coupons/delete/<?= $coupon['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
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
