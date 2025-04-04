<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.card-header {
    background: var(--header-bg) !important;
    border-bottom: 1px solid var(--border-color);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-btn);
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.btn-primary {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-primary:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    transform: translateY(-1px);
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Chỉnh sửa đơn hàng</h1>
    
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Cập nhật thông tin đơn hàng</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="order_code" class="form-label">Mã đơn hàng</label>
                    <input type="text" class="form-control" id="order_code" name="order_code" 
                        value="<?= htmlspecialchars($order['order_code']) ?>" required>
                    
                    <label for="user_name" class="form-label mt-3">Tên người dùng</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" 
                        value="<?= htmlspecialchars($order['user_name']) ?>" readonly>
                    
                    <label for="total_price" class="form-label mt-3">Tổng tiền</label>
                    <input type="number" class="form-control" id="total_price" name="total_price" 
                        value="<?= htmlspecialchars($order['total_price']) ?>" required>
                    
                    <label for="status" class="form-label mt-3">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Đang chờ</option>
                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                    
                    <label for="payment_status" class="form-label mt-3">Trạng thái thanh toán</label>
                    <select class="form-select" id="payment_status" name="payment_status">
                        <option value="pending" <?= $order['payment_status'] == 'pending' ? 'selected' : '' ?>>Đang chờ</option>
                        <option value="completed" <?= $order['payment_status'] == 'completed' ? 'selected' : '' ?>>Đã thanh toán</option>
                        <option value="failed" <?= $order['payment_status'] == 'failed' ? 'selected' : '' ?>>Thất bại</option>
                    </select>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                    <a href="/admin/orders" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
