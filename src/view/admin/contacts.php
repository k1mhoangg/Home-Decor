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
                <h2 class="page-title">Liên hệ khách hàng</h2>
                <div class="text-muted">Quản lý các yêu cầu liên hệ</div>
            </div>
            <div class="col-auto ms-auto">
                <!-- optional tools -->
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Trạng thái</th>
                            <th>Ngày</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($contacts)):
                            foreach ($contacts as $c): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['id']); ?></td>
                                    <td><?php echo htmlspecialchars($c['name']); ?></td>
                                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                                    <td><?php echo htmlspecialchars($c['subject'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($c['status']); ?></td>
                                    <td><?php echo htmlspecialchars($c['created_at'] ?? ''); ?></td>
                                    <td class="text-end">
                                        <a href="/admin/contacts/view?id=<?php echo urlencode($c['id']); ?>"
                                            class="btn btn-sm btn-outline-secondary">Xem</a>
                                        <form method="POST" action="/admin/contacts/delete" style="display:inline-block"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($c['id']); ?>">
                                            <button class="btn btn-sm btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Không có liên hệ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include __DIR__ . '/partial/pagination.php'; ?>

    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>