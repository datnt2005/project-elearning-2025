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
    <h1 class="mt-4 text-center">Danh sách Đơn Hàng</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white" style="background: #69BA31;">
            <h5><i class="fas fa-box me-2"></i>Quản lý Đơn Hàng</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>khóa học</th>
                        <th>Tổng giá trị</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= $order['order_code'] ?></td>
                            <td><?= $order['user_name'] ?></td>
                            <td><?= $order['course_title'] ?></td>
                            <td><?= number_format($order['total_price'], 0, ',', '.') ?> VND</td>
                            <td><?= $order['created_at'] ?></td>
                            <td><?= $order['status'] == 'pending' ? 'Đang xử lý' : ($order['status'] == 'completed' ? 'Hoàn thành' : 'Đã hủy') ?></td>
                            <td>
                                <a href="orders/edit/<?= $order['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <button class="btn btn-danger btn-sm delete-order" data-id="<?= $order['id'] ?>">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-order').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-id');
            Swal.fire({
                title: "Bạn có chắc muốn xóa?",
                text: "Hành động này không thể hoàn tác!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "orders/delete/" + orderId;
                }
            });
        });
    });
</script>
