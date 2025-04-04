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

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-btn);
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.btn-success {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-success:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    transform: translateY(-1px);
}
</style>

<div class="container-fluid px-4">
  <h1 class="mt-4 text-center" style="color: var(--header-bg);">Chỉnh sửa khóa học</h1>
  <div class="card shadow-sm">
    <div class="card-body">
      <!-- Hiển thị lỗi nếu có -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="/admin/courses/update/<?= $course['id'] ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $course['id'] ?>">
        <div class="mb-3">
          <label for="title" class="form-label">Tiêu đề</label>
          <input type="text" id="title" name="title" class="form-control" placeholder="Nhập tiêu đề khóa học" required
                 value="<?= isset($old_data['title']) ? htmlspecialchars($old_data['title']) : htmlspecialchars($course['title']) ?>">
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Mô tả</label>
          <textarea id="description" name="description" class="form-control" placeholder="Nhập mô tả"><?= isset($old_data['description']) ? htmlspecialchars($old_data['description']) : htmlspecialchars($course['description']) ?></textarea>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" id="price" name="price" class="form-control" placeholder="Nhập giá" required
                   value="<?= isset($old_data['price']) ? htmlspecialchars($old_data['price']) : htmlspecialchars($course['price']) ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label for="discount_price" class="form-label">Giá khuyến mãi</label>
            <input type="number" id="discount_price" name="discount_price" class="form-control" placeholder="Nhập giá khuyến mãi"
                   value="<?= isset($old_data['discount_price']) ? htmlspecialchars($old_data['discount_price']) : htmlspecialchars($course['discount_price']) ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label for="duration" class="form-label">Thời lượng</label>
            <input type="text" id="duration" name="duration" class="form-control" placeholder="VD: 10 giờ"
                   value="<?= isset($old_data['duration']) ? htmlspecialchars($old_data['duration']) : htmlspecialchars($course['duration']) ?>">
          </div>
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Ảnh khóa học</label>
          <input type="file" id="image" name="image" class="form-control">
          <?php if ($course['image']): ?>
            <img src="http://localhost:8000/<?= htmlspecialchars($course['image']) ?>" alt="Ảnh khóa học" width="100" class="mt-2">
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label for="video_intro" class="form-label">Video giới thiệu</label>
          <input type="file" id="video_intro" name="video_intro" class="form-control">
          <?php if ($course['video_intro']): ?>
            <video src="http://localhost:8000/<?= htmlspecialchars($course['video_intro']) ?>" controls width="25%" class="mt-2"></video>
          <?php endif; ?>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="category_id" class="form-label">Danh mục</label>
            <select id="category_id" name="category_id" class="form-control">
              <option value="<?= $course['category_id'] ?>"><?= htmlspecialchars($course['category_name']) ?></option>
              <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label for="subcategory_id" class="form-label">Danh mục con</label>
            <select id="subcategory_id" name="subcategory_id" class="form-control">
              <option value="<?= $course['subcategory_id'] ?>"><?= htmlspecialchars($course['subcategory_name']) ?></option>
              <?php foreach ($subcategories as $subcategory): ?>
                <option value="<?= $subcategory['id'] ?>"><?= htmlspecialchars($subcategory['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Trạng thái</label>
          <select id="status" name="status" class="form-control">
            <option value="1" <?= (isset($old_data['status']) ? $old_data['status'] == '1' : $course['status'] == '1') ? 'selected' : '' ?>>Hoạt động</option>
            <option value="0" <?= (isset($old_data['status']) ? $old_data['status'] == '0' : $course['status'] == '0') ? 'selected' : '' ?>>Tạm dừng</option>
          </select>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>
