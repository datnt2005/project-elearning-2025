<h1>Edit Category</h1>
<form method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Category Name</label>
        <input type="text" class="form-control" id="name" name="name" 
            value="<?= htmlspecialchars($categories['name']) ?>" required>
        
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description" name="description" 
            value="<?= htmlspecialchars($categories['description']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="/categories" class="btn btn-secondary">Cancel</a>
</form>
