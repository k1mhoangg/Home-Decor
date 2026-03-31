<?php include __DIR__ . '/partial/header.php'; ?>

<?php
if (class_exists(\Core\Session::class)) {
    $flash = \Core\Session::getFlash();
} else {
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $flash = $_SESSION['flash'] ?? null;
    if ($flash)
        unset($_SESSION['flash']);
}
?>

<?php include __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 page-header">
            <div class="col">
                <h2 class="page-title">Hồ sơ cá nhân</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="/admin" class="btn btn-outline-secondary">Quay lại Dashboard</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div
                class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : (($flash['type'] === 'error') ? 'alert alert-danger' : 'alert alert-info'); ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin cá nhân</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/admin/profile" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Avatar</label>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($user->getAvatar())): ?>
                                        <img src="<?php echo htmlspecialchars($user->getAvatar()); ?>" alt="avatar"
                                            style="width:64px;height:64px;object-fit:cover;border-radius:50%">
                                    <?php else: ?>
                                        <div
                                            style="width:64px;height:64px;border-radius:50%;background:#ddd;display:flex;align-items:center;justify-content:center;color:#777;">
                                            N/A</div>
                                    <?php endif; ?>
                                    <input type="file" name="avatar" accept="image/*" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tên hiển thị</label>
                                <input name="display_name"
                                    value="<?php echo htmlspecialchars($user->getDisplayName() ?? $user->getUsername()); ?>"
                                    class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" value="<?php echo htmlspecialchars($user->getEmail() ?? ''); ?>"
                                    class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập</label>
                                <input name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>"
                                    class="form-control" />
                            </div>

                            <button class="btn btn-primary">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Đổi mật khẩu</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/admin/profile/password">
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" name="confirm_password" class="form-control" required />
                            </div>
                            <button class="btn btn-primary">Đổi mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>