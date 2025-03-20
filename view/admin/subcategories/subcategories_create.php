<h1>Create Subcategories</h1>
<form method="POST">
    <div class="mb-3">
        <label for="category_id" class="form-label">Category_id</label>
        <select class="form-control" id="category_id" name="category_id">
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="name" class="form-label">Name category</label>
        <input type="text" class="form-control" id="name" name="name" required>
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description" name="description">
    </div>
    <button type="submit" class="btn btn-success">Create</button>
</form>