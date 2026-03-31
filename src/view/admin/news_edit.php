<?php
// view/admin/news_edit.php
require_once BASE_PATH . 'view/admin/partial/header.php';
require_once BASE_PATH . 'view/admin/partial/navbar.php';
?>
<div class="container py-4">
    <h1 class="mb-4"><?= isset($news['id']) ? 'Sửa bài viết' : 'Thêm bài viết mới' ?></h1>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($news['title'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Danh mục</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($news['category'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label>
            <?php if (!empty($news['thumbnail'])): ?>
                <div class="mb-2"><img src="/<?= htmlspecialchars($news['thumbnail']) ?>" style="width:120px;height:80px;object-fit:cover;border-radius:6px"></div>
            <?php endif; ?>
            <input type="file" name="thumbnail_file" accept="image/*" class="form-control" />
            <input type="hidden" name="thumbnail" value="<?= htmlspecialchars($news['thumbnail'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Nội dung</label>
            <textarea name="content" class="form-control" rows="8" required><?= htmlspecialchars($news['content'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="is_published" class="form-control">
                <option value="1" <?= (isset($news['is_published']) && $news['is_published']) ? 'selected' : '' ?>>Xuất bản</option>
                <option value="0" <?= (isset($news['is_published']) && !$news['is_published']) ? 'selected' : '' ?>>Nháp</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="/admin/news" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php require_once BASE_PATH . 'view/admin/partial/footer.php'; ?>
