<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách bài viết</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .table-actions .btn {
            margin: 0 2px;
            padding: 0.25rem 0.5rem;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
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

        .btn-outline-info,
        .btn-outline-primary,
        .btn-outline-danger {
            border-width: 1px;
        }

        .thumbnail-container {
            width: 80px;
            height: 60px;
            overflow: hidden;
            border-radius: 4px;
        }

        .thumbnail-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

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
        }
    </style>
</head>

<body>

    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center" style="color: var(--header-bg);">Danh sách bài viết</h1>

        <!-- Alert messages -->
        <?php if (isset($_SESSION["success_message"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION["success_message"] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION["success_message"]); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION["error_message"])): ?>
            <div class="alert alert-danger"><?= $_SESSION["error_message"] ?></div>
            <?php unset($_SESSION["error_message"]); ?>
        <?php endif; ?>

        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-start align-items-center text-white">
                <h5 class="mb-0 me-3"><i class="fas fa-newspaper me-2"></i>Quản lý bài viết</h5>
                <a href="/admin/post/create" class="btn btn-add">
                    <i class="fas fa-plus me-1"></i> Thêm bài viết
                </a>
            </div>
            <div class="card-body">
                <!-- Add filter structure -->
                <div class="filter-container">
                    <div class="search-group">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm bài viết...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="filter-group">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Tất cả danh mục</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select class="form-select" id="authorFilter">
                            <option value="">Tất cả tác giả</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Tác giả</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($posts)): ?>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($post['id']) ?></td>
                                        <td>
                                            <div class="thumbnail-container">
                                                <?php if (!empty($post['thumbnail'])): ?>
                                                    <img src="../uploads/<?= htmlspecialchars($post['thumbnail']) ?>" alt="Thumbnail">
                                                <?php else: ?>
                                                    <span class="text-muted">Không có ảnh</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            <div class="fw-bold"><?= htmlspecialchars($post['title']) ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($post['name']) ?></td>
                                        <td><?= date("d/m/Y H:i", strtotime($post['created_at'])) ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/admin/post/<?= $post['id'] ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/admin/post/edit/<?= $post['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete(<?= $post['id'] ?>)" 
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Không có bài viết nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Bạn có chắc chắn muốn xóa bài viết này?')) {
                window.location.href = '/admin/post/delete/' + id;
            }
        }

        // Add filter functionality
        function populateFilters() {
            const rows = document.querySelectorAll('tbody tr');
            const authors = new Set();
            
            rows.forEach(row => {
                const authorName = row.cells[3].textContent.trim();
                if (authorName !== '') {
                    authors.add(authorName);
                }
            });
            
            const authorFilter = document.getElementById('authorFilter');
            Array.from(authors).sort().forEach(author => {
                const option = new Option(author, author);
                authorFilter.add(option);
            });
        }

        function filterPosts() {
            const searchText = document.getElementById('searchInput').value.toLowerCase().trim();
            const authorFilter = document.getElementById('authorFilter').value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const title = row.cells[2].textContent.toLowerCase();
                const author = row.cells[3].textContent.trim();
                
                let showRow = true;
                
                if (searchText) {
                    showRow = title.includes(searchText);
                }
                
                if (authorFilter && author !== authorFilter) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            populateFilters();
        });

        document.getElementById('searchInput').addEventListener('input', filterPosts);
        document.getElementById('authorFilter').addEventListener('change', filterPosts);
        document.getElementById('categoryFilter').addEventListener('change', filterPosts);
    </script>

</body>