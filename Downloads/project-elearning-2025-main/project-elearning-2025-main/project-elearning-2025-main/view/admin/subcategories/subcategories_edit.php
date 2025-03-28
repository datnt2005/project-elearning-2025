<h1>Edit Subcategory</h1>
<form method="POST">
    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" id="category_id" name="category_id">
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" 
                    <?= ($category['id'] == $subcategory['category_id']) ? 'selected' : '' ?>>
                    <?= $category['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label for="name" class="form-label">Subcategory Name</label>
        <input type="text" class="form-control" id="name" name="name" 
            value="<?= htmlspecialchars($subcategory['name']) ?>" required>
        
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description" name="description" 
            value="<?= htmlspecialchars($subcategory['description']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="/admin/subcategories" class="btn btn-secondary">Cancel</a>
</form>
