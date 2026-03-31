<?php
// view/admin/news_comments.php
require_once BASE_PATH . 'view/admin/partial/header.php';
require_once BASE_PATH . 'view/admin/partial/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4">Quản lý bình luận bài viết: <span class="text-primary">#<?= $news['id'] ?> - <?= htmlspecialchars($news['title']) ?></span></h1>
    <a href="/admin/news" class="btn btn-secondary mb-3">Quay lại danh sách bài viết</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người bình luận</th>
                <th>Nội dung</th>
                <th>Ngày gửi</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($comments)): foreach ($comments as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['author']) ?></td>
                <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
                <td><?= $c['created_at'] ?></td>
                <td>
                    <form method="post" action="/admin/news/<?= $news['id'] ?>/comments/<?= $c['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Xác nhận xóa bình luận?');">
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="5" class="text-center">Chưa có bình luận nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once BASE_PATH . 'view/admin/partial/footer.php'; ?>
