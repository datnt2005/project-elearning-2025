<style>
:root {
    --header-bg: #343a40;
    --primary-btn: #dc3545;
    --primary-btn-hover: #bb2d3b;
    --text-light: #f8f9fa;
    --border-color: rgba(255, 255, 255, 0.1);
}

/* Card and basic styles */
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background: var(--header-bg) !important;
    border-bottom: 1px solid var(--border-color);
}

/* Button styles */
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

/* Filter styles */
.filter-container {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.search-group {
    flex: 2;
    min-width: 300px;
}

/* Table styles */
.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 85%;
}

@media (max-width: 768px) {
    .filter-container {
        flex-direction: column;
        gap: 1rem;
    }

    .filter-group,
    .search-group {
        width: 100%;
        min-width: 100%;
    }

    .input-group {
        margin-bottom: 0;
    }

    .btn-group {
        flex-wrap: nowrap;
        gap: 0.5rem;
    }

    .btn-group .btn {
        flex: 1;
    }
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Danh sách Đánh giá</h1>

    <div class="card mb-4">
    <div class="card-header d-flex justify-content-start align-items-center text-white">
            <h5 class="mb-0 me-3">
                <i class="fas fa-comment-alt me-2"></i>Quản lý Bình luận
            </h5>
            <a href="/admin/reviews/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Thêm Bình luận
            </a>
        </div>
        <div class="card-body">
        <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm bình luận...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <select class="form-select" id="statusFilter">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1">Hoạt động</option>
                        <option value="0">Không hoạt động</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khóa học</th>
                        <th>Người dùng</th>
                        <th>Đánh giá sao</th>
                        <th>Bình luận</th>
                        <th>Lượt thích</th>
                        <th>Ảnh</th>
                        <th>Phản hồi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= $review['id'] ?></td>
                        <td><?= $review['title'] ?></td>
                        <td><?= $review['user_name'] ?></td>
                        <td><?= $review['rating'] ?> <i class="fas fa-star text-warning"></i></td>
                        <td><?= $review['comment'] ?></td>
                        <td><?= $review['like_count'] ?></td>
                        <td>
                            <?php if (!empty($review['images'])): ?>
                            <?php foreach ($review['images'] as $image): ?>
                            <img src="/<?= $image ?>" width="50" class="rounded">
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($review['replies'])): ?>
                            <?php foreach ($review['replies'] as $reply): ?>
                            <p><b><?= $reply['user_name'] ?>:</b> <?= $reply['comment'] ?> <br>
                                <small>(<?= $reply['created_at'] ?>)</small></p>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td>

                            <a href="/admin/reviews/edit/<?= $review['id'] ?>" class="btn btn-sm btn-outline-primary"
                                title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="confirmDelete(<?= $review['id'] ?>)" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa bình luận này?')) {
        window.location.href = '/admin/reviews/delete/' + id;
    }
}

function filterRatings() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const code = row.cells[1].textContent.toLowerCase();
        const description = row.cells[2].textContent.toLowerCase();
        const status = row.querySelector('.badge').textContent.trim() === 'Hoạt động' ? '1' : '0';

        let showRow = true;

        if (searchText) {
            showRow = code.includes(searchText) || description.includes(searchText);
        }

        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('input', filterRatings);
document.getElementById('statusFilter').addEventListener('change', filterRatings);
</script>