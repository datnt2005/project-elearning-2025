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
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Chỉnh sửa Coupon</h1>
    <div class="card shadow-sm">
        <div class="card-header text-white">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Chỉnh sửa Coupon</h5>
        </div>
        <div class="card-body">
            <h1>Edit Coupon</h1>
            <form method="POST">
                <div class="mb-3">
                    <label for="code" class="form-label">Coupon Code</label>
                    <input type="text" class="form-control" id="code" name="code" 
                        value="<?= htmlspecialchars($coupon['code']) ?>" required>
                    
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description" 
                        value="<?= htmlspecialchars($coupon['description']) ?>">
                    
                    <label for="discount_percent" class="form-label">Discount Percent</label>
                    <input type="number" class="form-control" id="discount_percent" name="discount_percent" 
                        value="<?= htmlspecialchars($coupon['discount_percent']) ?>" required>
                    
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="<?= htmlspecialchars($coupon['start_date']) ?>" required>
                    
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="<?= htmlspecialchars($coupon['end_date']) ?>" required>
                    
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1" <?= $coupon['status'] == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= $coupon['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/coupons" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>