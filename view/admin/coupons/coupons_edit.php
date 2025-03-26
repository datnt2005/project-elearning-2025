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