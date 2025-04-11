<style>
    :root {
        --header-bg: #343a40;
        --primary-btn: #198754;
        --primary-btn-hover: #157347;
        --text-light: #f8f9fa;
        --border-color: rgba(255, 255, 255, 0.1);
    }

    h1.text-center {
        color: var(--header-bg) !important;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
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
    <h1 class="mt-4 text-center">Gửi thông báo</h1>

    <div class="card mb-4">
        <div class="card-header text-white">
            <i class="fas fa-paper-plane me-2"></i>Gửi thông báo mới
        </div>
        <div class="card-body">
            <form method="post" action="/admin/notifications/store">
                <div class="mb-3">
                    <label for="user_id" class="form-label">Gửi đến</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">-- Chọn người nhận --</option>
                        <option value="all">Tất cả người dùng</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Nội dung thông báo</label>
                    <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="link" class="form-label">Link liên kết (nếu có)</label>
                    <input type="text" name="link" id="link" class="form-control">
                </div>

                <button type="submit" class="btn btn-add">
                    <i class="fas fa-paper-plane me-1"></i> Tạo thông báo
                </button>
            </form>
        </div>
    </div>
</div>