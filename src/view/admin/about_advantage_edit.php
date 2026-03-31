<?php require __DIR__ . '/partial/header.php'; ?>

<?php
if (class_exists(\Core\Session::class))
    $flash = \Core\Session::getFlash();
else {
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $flash = $_SESSION['flash'] ?? null;
    if ($flash)
        unset($_SESSION['flash']);
}
?>

<?php require __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 g-2 align-items-center page-header">
            <div class="col">
                <h2 class="page-title">
                    <?php echo $isEdit ? 'Cập nhật ưu thế nổi bật' : 'Thêm ưu thế nổi bật'; ?>
                </h2>
                <div class="text-muted">Quản lý các ưu thế nổi bật hiển thị trên trang Giới thiệu</div>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/about/save-advantage">
                    <input type="hidden" name="tab" value="advantages">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($advantage['id'] ?? 0); ?>">

                    <div class="mb-3">
                        <label class="form-label">Nội dung</label>
                        <input type="text" name="content" class="form-control"
                               value="<?php echo htmlspecialchars($advantage['content'] ?? ''); ?>" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="display_order"
                               value="<?php echo htmlspecialchars($advantage['display_order'] ?? 0); ?>"
                               class="form-control" required />
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? 'Cập nhật' : 'Thêm'; ?>
                        </button>
                        <a href="/admin/about?tab=advantages" class="btn btn-link">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partial/footer.php'; ?>


