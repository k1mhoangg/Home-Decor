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
                    <?php echo $isEdit ? 'Cập nhật ảnh nội thất trang trí' : 'Thêm ảnh nội thất trang trí'; ?>
                </h2>
                <div class="text-muted">Quản lý ảnh slide nội thất trang Giới thiệu</div>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/about/save-decor-image" enctype="multipart/form-data" id="decorEditForm">
                    <input type="hidden" name="tab" value="decor-images">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($decorImage['id'] ?? 0); ?>">

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Ảnh nội thất</label>
                            <small class="form-hint d-block mb-2 text-danger">
                                <strong>Yêu cầu:</strong> Ảnh phải có kích thước tối thiểu 1200x600px
                            </small>

                            <?php if (!empty($decorImage['image_url'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo htmlspecialchars($decorImage['image_url_thumb'] ?? $decorImage['image_url']); ?>"
                                         style="max-width:300px;max-height:200px;object-fit:cover;border-radius:6px">
                                </div>
                            <?php endif; ?>

                            <input type="file" name="image" id="decor_image_file" accept="image/*" class="form-control" <?php echo empty($decorImage) ? 'required' : ''; ?> />
                            <div id="decor_image_error" class="text-danger mt-2" style="display:none;"></div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="display_order"
                                   value="<?php echo htmlspecialchars($decorImage['display_order'] ?? 0); ?>"
                                   class="form-control" required />
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? 'Cập nhật' : 'Thêm ảnh'; ?>
                        </button>
                        <a href="/admin/about?tab=decor-images" class="btn btn-link">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/public/js/about.js"></script>

<?php require __DIR__ . '/partial/footer.php'; ?>


