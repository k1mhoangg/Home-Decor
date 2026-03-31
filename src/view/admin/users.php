<?php
// Include admin partial header (Tabler assets)
include __DIR__ . '/partial/header.php';

// Hiển thị flash (sử dụng Core\Session nếu có)
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
                <h2 class="page-title">Quản lý người dùng</h2>
                <div class="text-muted">Danh sách người dùng hệ thống</div>
            </div>
            <div class="col-auto ms-auto">
                <a href="/admin/users/create" class="btn btn-primary">Thêm user</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <?php
            $cls = ($flash['type'] ?? '') === 'success' ? 'alert alert-success' : 'alert alert-danger';
            ?>
            <div class="<?php echo $cls; ?> mb-3"><?php echo htmlspecialchars($flash['message'] ?? ''); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tổng: <?php echo count($users ?? []); ?> user</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Username</th>
                            <th>Display name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)):
                            foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($u->getId()); ?></td>
                                    <td style="width:64px">
                                        <?php if ($u->getAvatar()): ?>
                                            <img src="<?php echo htmlspecialchars($u->getAvatar()); ?>" alt="avatar" class="rounded"
                                                style="width:48px;height:48px;object-fit:cover">
                                        <?php else: ?>
                                            <div class="bg-gray-100 rounded d-inline-flex align-items-center justify-content-center"
                                                style="width:48px;height:48px;color:#777;">N/A</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($u->getUsername()); ?></td>
                                    <td><?php echo htmlspecialchars($u->getDisplayName() ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($u->getEmail() ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($u->getRole()); ?></td>
                                    <td class="text-end">
                                        <a href="/admin/users/edit?id=<?php echo urlencode($u->getId()); ?>"
                                            class="btn btn-sm btn-outline-secondary">Sửa</a>

                                        <form method="POST" action="/admin/users/delete" style="display:inline-block"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa user này?');">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($u->getId()); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" class="text-muted text-center">Không có người dùng</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include __DIR__ . '/partial/pagination.php'; ?>

    </div>
</div>

<?php
// include admin partial footer
include __DIR__ . '/partial/footer.php';
?>