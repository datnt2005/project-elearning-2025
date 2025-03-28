<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Chỉnh sửa khóa học</h1>
    <div class="">
        <div class="card-body">
            <form method="POST" action="/admin/courses/update/<?= $course['id'] ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $course['id'] ?>">
                <div class="mb-3">
                    <label>Tiêu đề</label>
                    <input type="text" name="title" class="form-control" value="<?= $course['title'] ?>" required>
                </div>
                <div class="mb-3">
                    <label>Mô tả</label>
                    <textarea name="description" class="form-control"><?= $course['description'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Giá</label>
                    <input type="number" name="price" class="form-control" value="<?= $course['price'] ?>" required>
                </div>
                <div class="mb-3">
                    <label>Giá khuyến mãi</label>
                    <input type="number" name="discount_price" class="form-control" value="<?= $course['discount_price'] ?>">
                </div>
                <div class="mb-3">
                    <label>Thời lượng</label>
                    <input type="text" name="duration" class="form-control" value="<?= $course['duration'] ?>">
                </div>
                <div class="mb-3">
                    <label>Ảnh khóa học</label>
                    <input type="file" name="image" class="form-control">
                    <?php if($course['image']) { ?>
                    <img src="http://localhost:8000/<?= htmlspecialchars($course['image']) ?>" width="100">
                    <?php } ?>
                    </div>
                <div class="mb-3">
                    <label>Video giới thiệu</label>
                    <input type="file" name="video_intro" class="form-control">
                </div>
                <?php if($course['video_intro']) { ?>
                <video src="http://localhost:8000/<?= htmlspecialchars($course['video_intro']) ?>" controls width="25%"></video>
                <?php } ?>
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_id" class="form-control">
                        <option value="<?= $course['category_id'] ?>"><?= $course['category_name'] ?></option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Danh mục con</label>
                    <select name="subcategory_id" class="form-control">
                        <option value="<?= $course['subcategory_id'] ?>"><?= $course['subcategory_name'] ?></option>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <option value="<?= $subcategory['id'] ?>"><?= htmlspecialchars($subcategory['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= $course['status'] ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= !$course['status'] ? 'selected' : '' ?>>Tạm dừng</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>
