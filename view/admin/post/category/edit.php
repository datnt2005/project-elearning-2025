<style>
    /* Reset margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Custom container styling */
.custom-container {
    width: 100%;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Centered header */
.custom-header {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

/* Form container styling */
.form-container {
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
}

/* Label styling */
.form-label {
    font-size: 16px;
    color: #555;
}

/* Input field styling */
.form-control {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 5px;
    outline: none;
}

.form-control:focus {
    border-color: #007bff;
}

/* Button styling */
.btn {
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

/* Submit button */
.custom-btn-submit {
    background-color: #007bff;
    color: white;
    border: none;
}

.custom-btn-submit:hover {
    background-color: #0056b3;
}

/* Back button */
.custom-btn-back {
    background-color: #f8f9fa;
    color: #333;
    border: 1px solid #ccc;
}

.custom-btn-back:hover {
    background-color: #e2e6ea;
    color: #0056b3;
}

/* Error message */
.alert {
    background-color: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert ul {
    list-style-type: none;
}

.alert li {
    margin-bottom: 5px;
}

/* Responsive Design */
@media (max-width: 600px) {
    .custom-container {
        padding: 15px;
    }

    .form-control {
        font-size: 14px;
    }

    .btn {
        font-size: 14px;
    }
}

</style>
<div class="custom-container">
    <h2 class="text-center custom-header">Chỉnh sửa danh mục</h2>

    <div class="form-container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="name" name="name" 
                    value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn custom-btn-submit">Cập nhật</button>
                <a href="/admin/postCategory" class="btn custom-btn-back">Quay lại</a>
            </div>
        </form>
    </div>
</div>