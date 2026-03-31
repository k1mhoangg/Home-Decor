<?php
// view/admin/news.php
// Trang quản lý danh sách bài viết cho admin
require_once BASE_PATH . 'view/admin/partial/header.php';
require_once BASE_PATH . 'view/admin/partial/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4">Quản lý bài viết (Tin tức)</h1>
    <a href="/admin/news/create" class="btn btn-success mb-3">Thêm bài viết mới</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Danh mục</th>
                <th>Ngày đăng</th>
                <th>Trạng thái</th>
                <th>Lượt xem</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($newsList)): foreach ($newsList as $n): ?>
            <tr>
                <td><?= $n['id'] ?></td>
                <td><?= htmlspecialchars($n['title']) ?></td>
                <td><?= htmlspecialchars($n['category']) ?></td>
                <td><?= htmlspecialchars($n['created_at']) ?></td>
                <td><?= $n['is_published'] ? 'Xuất bản' : 'Nháp' ?></td>
                <td><?= $n['views'] ?></td>
                <td>
                    <a href="/admin/news/<?= $n['id'] ?>/edit" class="btn btn-sm btn-primary">Sửa</a>
                    <a href="/admin/news/<?= $n['id'] ?>/comments" class="btn btn-sm btn-info">Bình luận</a>
                    <form method="post" action="/admin/news/<?= $n['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Xác nhận xóa?');">
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7" class="text-center">Chưa có bài viết nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once BASE_PATH . 'view/admin/partial/footer.php'; ?>
