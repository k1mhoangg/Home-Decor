<?php
// view/admin/news_all_comments.php
require_once BASE_PATH . 'view/admin/partial/header.php';
require_once BASE_PATH . 'view/admin/partial/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4">Quản lý tất cả bình luận bài viết</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bài viết</th>
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
                <td><a href="/news/<?= $c['news_id'] ?>" target="_blank">#<?= $c['news_id'] ?> - <?= htmlspecialchars($c['news_title']) ?></a></td>
                <td><?= htmlspecialchars($c['author']) ?></td>
                <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
                <td><?= $c['created_at'] ?></td>
                <td>
                    <form method="post" action="/admin/news/comments/<?= $c['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Xác nhận xóa bình luận?');">
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6" class="text-center">Chưa có bình luận nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once BASE_PATH . 'view/admin/partial/footer.php'; ?>
