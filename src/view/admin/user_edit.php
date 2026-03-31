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
        <div class="row g-2 align-items-center page-header">
            <div class="col">
                <h2 class="page-title">
                    <?php echo $isEdit ? 'Cập nhật người dùng' : 'Thêm người dùng mới'; ?>
                </h2>
                <div class="text-muted">
                    <?php echo $isEdit ? 'Chỉnh sửa thông tin người dùng' : 'Tạo tài khoản người dùng mới'; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/users/save" enctype="multipart/form-data">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user->getId()); ?>">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Username</label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?php echo $user ? htmlspecialchars($user->getUsername()) : ''; ?>" 
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo $user ? htmlspecialchars($user->getEmail() ?? '') : ''; ?>" 
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Name</label>
                            <input type="text" name="display_name" class="form-control" 
                                   value="<?php echo $user ? htmlspecialchars($user->getDisplayName() ?? '') : ''; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="customer" <?php echo (!$user || $user->getRole() === 'customer') ? 'selected' : ''; ?>>
                                    Customer
                                </option>
                                <option value="admin" <?php echo ($user && $user->getRole() === 'admin') ? 'selected' : ''; ?>>
                                    Admin
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label <?php echo !$isEdit ? 'required' : ''; ?>">
                                Mật khẩu <?php echo $isEdit ? '(để trống nếu không đổi)' : ''; ?>
                            </label>
                            <input type="password" name="password" class="form-control" 
                                   <?php echo !$isEdit ? 'required' : ''; ?>>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Avatar</label>
                            <?php if ($user && $user->getAvatar()): ?>
                                <div class="mb-2">
                                    <img src="<?php echo htmlspecialchars($user->getAvatar()); ?>" 
                                         alt="avatar" class="rounded"
                                         style="width:80px;height:80px;object-fit:cover">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            <small class="form-hint">Chỉ chấp nhận file ảnh (jpg, png, gif, webp)</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $isEdit ? 'Cập nhật' : 'Tạo người dùng'; ?>
                        </button>
                        <a href="/admin/users" class="btn btn-link">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>
