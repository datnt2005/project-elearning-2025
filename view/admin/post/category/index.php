<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách danh mục bài viết</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
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

        .filter-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .search-group {
            flex: 1;
            min-width: 300px;
        }

        .btn-group {
            display: flex;
            gap: 0.25rem;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .btn-group .btn:hover {
            transform: translateY(-1px);
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .filter-container {
                flex-direction: column;
                gap: 1rem;
            }

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
</head>
<body>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách danh mục bài viết</h1>

    <?php if (isset($_SESSION["success_message"])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION["success_message"] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION["success_message"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["error_message"])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION["error_message"] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION["error_message"]); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-start align-items-center text-white">
            <h5 class="mb-0 me-3">
                <i class="fas fa-folder me-2"></i>Quản lý danh mục bài viết
            </h5>
            <a href="/admin/postCategory/create" class="btn btn-add">
                <i class="fas fa-plus me-1"></i> Thêm danh mục
            </a>
        </div>
        <div class="card-body">
            <div class="filter-container">
                <div class="search-group">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm danh mục...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">ID</th>
                            <th>Tên danh mục</th>
                            <th width="20%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($postCategories)): ?>
                            <?php foreach ($postCategories as $postCategory): ?>
                                <tr>
                                    <td><?= htmlspecialchars($postCategory['id']) ?></td>
                                    <td class="text-start fw-bold">
                                        <?= htmlspecialchars($postCategory['name']) ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/admin/postCategory/edit/<?= $postCategory['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete(<?= $postCategory['id'] ?>)" 
                                                    title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Không có danh mục nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        window.location.href = '/admin/postCategory/delete/' + id;
    }
}

function filterCategories() {
    const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        row.style.display = name.includes(searchText) ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('input', filterCategories);
</script>
</body>
</html>
