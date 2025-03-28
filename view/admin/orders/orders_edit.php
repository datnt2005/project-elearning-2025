<h1>Edit Order</h1>
<form method="POST">
    <div class="mb-3">
        <label for="order_code" class="form-label">Order Code</label>
        <input type="text" class="form-control" id="order_code" name="order_code" 
            value="<?= htmlspecialchars($order['order_code']) ?>" required>
        
        <label for="user_name" class="form-label">User Name</label>
        <input type="text" class="form-control" id="user_name" name="user_name" 
            value="<?= htmlspecialchars($order['user_name']) ?>" readonly>
        
        <label for="total_price" class="form-label">Total Price</label>
        <input type="number" class="form-control" id="total_price" name="total_price" 
            value="<?= htmlspecialchars($order['total_price']) ?>" required>
        
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        
        <label for="payment_status" class="form-label">Payment Status</label>
        <select class="form-control" id="payment_status" name="payment_status">
            <option value="pending" <?= $order['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= $order['payment_status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="failed" <?= $order['payment_status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
        </select>
        
       
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="/admin/orders" class="btn btn-secondary">Cancel</a>
</form>
