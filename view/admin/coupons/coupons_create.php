<h1>Create Coupon</h1>
<form method="POST">
    <div class="mb-3">
        <label for="code" class="form-label">Coupon Code</label>
        <input type="text" class="form-control" id="code" name="code" required>
        
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description" name="description">
        
        <label for="discount_percent" class="form-label">Discount Percent</label>
        <input type="number" class="form-control" id="discount_percent" name="discount_percent" required>
        
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date" required>
        
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date" required>
        
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Create</button>
</form>