<br>
<br>
<br>
<br>
<br>

<style>
    .container {
        margin-top: 1rem;
        text-align: center;
        margin-left: 150px;
    }

    .post-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .post-card {
        width: 400px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .post-card:hover {
        transform: translateY(-5px);
    }

    .post-card img {
        width: 100%;
        height: auto;
        display: block;
    }

    .post-body {
        padding: 16px;
    }

    .post-title {
        font-size: 1.25rem;
        margin-bottom: 8px;
    }

    .post-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .no-posts {
        color: #6c757d;
    }
</style>

<div class="container">
    <h2 class="text-3xl font-bold mb-4 text-primary">Các bài viết nổi bật</h2>
    <p class="text-decoration">Tổng hợp các bài viết chia sẻ về kinh nghiệm tự học lập trình online và các kỹ thuật lập trình web.
    </p>
    <br>
    <br>
    <?php if (empty($posts)): ?>
        <p class="no-posts">Chưa có bài viết nào.</p>
    <?php else: ?>
        <div class="post-grid">
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <a href="/posts/detail/<?= htmlspecialchars($post['id']) ?>" style="text-decoration: none; color: inherit;">
                        <?php if (!empty($post['thumbnail'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($post['thumbnail']) ?>"
                                alt="<?= htmlspecialchars($post['title']) ?>">
                        <?php endif; ?>
                        <div class="post-body">
                            <h5 class="post-title"><?= htmlspecialchars($post['title']) ?></h5>
                            <p class="post-meta">Danh Mục: <?php echo htmlspecialchars($post['category_name']) ?></p>

                            <p class="post-meta">Đăng bởi <strong><?= htmlspecialchars($post['name']) ?> </strong>vào
                                <?= date("d/m/Y", strtotime($post['created_at'])) ?></p>
                        </div>

                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
