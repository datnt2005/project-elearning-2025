<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #198754;
    --primary-btn-hover: #157347;
    --text-light: #f8f9fa;
    --border-color: rgba(255,255,255,0.1);
}

h1.text-center {
    color: var(--header-bg) !important;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.card-header {
    background: var(--header-bg) !important;
    border-bottom: 1px solid var(--border-color);
}

.btn-add {
    background-color: var(--primary-btn);
    border-color: var(--primary-btn);
    color: var(--text-light);
}

.btn-add:hover {
    background-color: var(--primary-btn-hover);
    border-color: var(--primary-btn-hover);
    color: var(--text-light);
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.badge {
    font-size: 85%;
}
</style>

<div class="container-fluid px-4">
  <h1 class="mt-4 text-center">Chỉnh sửa thông báo</h1>

  <div class="card mb-4">
    <div class="card-header text-white d-flex align-items-center">
      <h5 class="mb-0">
        <i class="fas fa-bell me-2"></i> Cập nhật nội dung thông báo
      </h5>
    </div>
    <div class="card-body">
      <form action="/admin/notifications/update/<?= $notification['id'] ?>" method="POST">
        <div class="mb-3">
          <label for="user_id" class="form-label">Người nhận</label>
          <select name="user_id" id="user_id" class="form-select">
            <option value="">-- Chọn người nhận --</option>
            <?php foreach ($users as $user): ?>
              <option value="<?= $user['id'] ?>" <?= $notification['user_id'] == $user['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($user['name']) ?> (<?= $user['email'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="message" class="form-label">Nội dung thông báo</label>
          <textarea name="message" id="message" rows="4" class="form-control" required><?= htmlspecialchars($notification['message']) ?></textarea>
        </div>

        <div class="mb-3">
          <label for="link" class="form-label">Link (tùy chọn)</label>
          <input type="text" name="link" id="link" class="form-control" value="<?= htmlspecialchars($notification['link']) ?>">
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Trạng thái</label>
          <select name="status" id="status" class="form-select">
            <option value="unread" <?= $notification['status'] === 'unread' ? 'selected' : '' ?>>Chưa đọc</option>
            <option value="read" <?= $notification['status'] === 'read' ? 'selected' : '' ?>>Đã đọc</option>
          </select>
        </div>

        <div class="d-flex justify-content-between">
          <a href="/admin/notifications" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Cập nhật
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
