<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Thêm khóa học</h1>
    <div class=" container">
        <div class="card-body">
            <form method="POST" action="/admin/courses/create" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Tiêu đề</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Giá</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Giá khuyến mãi</label>
                    <input type="number" name="discount_price" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Thời lượng</label>
                    <input type="text" name="duration" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Ảnh khóa học</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Video giới thiệu</label>
                    <input type="file" name="video_intro" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control">
                        <option value="">Chọn danh mục</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Danh mục con</label>
                    <select name="subcategory_id" class="form-control">
                        <option value="">Chọn danh mục con</option>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <option value="<?= $subcategory['id'] ?>"><?= htmlspecialchars($subcategory['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="1">Hoạt động</option>
                        <option value="0">Tạm dừng</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-submit">Thêm khóa học</button>
            </form>
        </div>
    </div>
</div>