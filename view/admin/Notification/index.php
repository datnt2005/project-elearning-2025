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
    <h1 class="mt-4 text-center">Danh sách thông báo</h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center text-white">
            <h5 class="mb-0">
                <i class="fas fa-bell me-2"></i>Quản lý thông báo
            </h5>
            <div>
                <a href="/admin/notifications/create" class="btn btn-add">
                    <i class="fas fa-plus me-1"></i> Thêm thông báo
                </a>
                <a href="#" class="btn btn-add" onclick="submitSelected('send-form'); return false;">
                    <i class="fas fa-paper-plane me-1"></i> Gửi thông báo
                </a>
                <a href="#" class="btn btn-danger" onclick="submitSelected('delete-form'); return false;">
                    <i class="fas fa-trash me-1"></i> Xóa đã chọn
                </a>
            </div>
        </div>
        <div class="card-body">
            <form id="send-form" action="/admin/notifications/send-selected" method="POST" style="display: none;">
                <input type="hidden" name="selected_ids" id="send-selected-ids">
            </form>
            <form id="delete-form" action="/admin/notifications/delete-selected" method="POST" style="display: none;">
                <input type="hidden" name="selected_ids" id="delete-selected-ids">
            </form>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="check-all"></th>
                            <th>ID</th>
                            <th>Người nhận</th>
                            <th>Nội dung</th>
                            <th>Trạng thái đọc</th>
                            <th>Trạng thái gửi</th>
                            <th>Ngày tạo</th>
                            <th>Link</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notifications as $notification): ?>
                            <tr>
                                <td><input type="checkbox" class="select-noti" value="<?= $notification['id'] ?>"></td>
                                <td><?= $notification['id'] ?></td>
                                <td>
                                    <?php if ($notification['user_id'] == 'all'): ?>
                                        <span class="text-muted">Tất cả</span>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                            <?php if ($user['id'] == $notification['user_id']): ?>
                                                <?= htmlspecialchars($user['name']) ?> (<?= $user['email'] ?>)
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>

                                <td><?= htmlspecialchars($notification['message']) ?></td>
                                <td>
                                    <span class="badge <?= $notification['status'] === 'read' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= ucfirst($notification['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $notification['send_status'] === 'sent' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= ucfirst($notification['send_status']) ?>
                                    </span>
                                </td>
                                <td><?= $notification['created_at'] ?></td>
                                <td>
                                    <?php if (!empty($notification['link'])): ?>
                                        <a href="<?= $notification['link'] ?>" target="_blank">Xem</a>
                                    <?php else: ?>
                                        <span class="text-muted">Không có</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/notifications/edit/<?= $notification['id'] ?>" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin/notifications/delete/<?= $notification['id'] ?>"
                                            class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa thông báo này?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($_SESSION['success_message'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '<?= $_SESSION['success_message'] ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error_message'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '<?= $_SESSION['error_message'] ?>',
            confirmButtonText: 'OK'
        });
    </script>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<script>
    document.getElementById('check-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.select-noti');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function submitSelected(formId) {
        const ids = Array.from(document.querySelectorAll('.select-noti:checked'))
            .map(cb => cb.value)
            .join(',');

        if (ids.length === 0) {
            alert('Vui lòng chọn ít nhất một thông báo.');
            return false;
        }

        // Map formId sang đúng id của input ẩn chứa selected_ids
        let inputId = '';
        if (formId === 'send-form') {
            inputId = 'send-selected-ids';
        } else if (formId === 'delete-form') {
            inputId = 'delete-selected-ids';
        }

        document.getElementById(inputId).value = ids;
        document.getElementById(formId).submit();
    }

    
</script>