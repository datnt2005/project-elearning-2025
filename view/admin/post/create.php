<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viết Blog</title>
    <meta name="description" content="Trang viết blog với Live Preview, hỗ trợ định dạng văn bản dễ dàng.">
    <meta name="keywords" content="viết blog, soạn thảo, chỉnh sửa văn bản">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        #editor-container {
            height: 50vh;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 16px;
            overflow: hidden;
            background-color: #ffffff;
        }
    </style>
</head>

<body>

    <div class="container p-4 mt-4">
        <div class="row">
            <div class="col-lg-9 col-md-8">
                <h2 class="text-3xl font-bold mb-4 text-primary">Viết Blog</h2>

                <!-- Hiển thị lỗi nếu có -->
                <?php if (!empty($_SESSION["error"])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION["error"]; ?>
                    </div>
                    <?php unset($_SESSION["error"]); ?>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Tiêu đề -->
                    <input type="text" name="title" placeholder="Nhập tiêu đề..."
                        class="form-control mb-3 p-3 rounded-xl shadow-md" required>

                    <!-- Chọn danh mục -->
                    <select name="category" class="form-control mb-3 p-2 rounded-xl shadow-md" required>
                        <option value="">Chọn danh mục</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Ảnh đại diện -->
                    <input type="file" name="thumbnail" class="form-control mb-3 p-2 rounded-xl shadow-md"
                        accept="image/*">

                    <!-- Nội dung bài viết -->
                    <div id="editor-container" class="form-control mb-3 p-3 rounded-xl shadow-md"
                        style="min-height: 200px;"></div>
                    <textarea name="content" id="hidden-content" style="display:none;"></textarea>

                    <!-- Nút đăng bài -->
                    <button type="submit" class="btn btn-primary mt-3">Đăng bài</button>
                </form>

                <!-- Quay lại -->
                <a href="/admin/post" class="btn btn-secondary mt-3">Quay lại</a>
            </div>
        </div>
    </div>

    <!-- Nhúng Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Nhập nội dung bài viết...',
        });

        document.querySelector('form').onsubmit = function () {
            document.querySelector('#hidden-content').value = quill.root.innerHTML;
        };
    </script>

</body>