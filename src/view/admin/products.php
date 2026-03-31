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
        <div class="row g-2 align-items-center page-header pl-2">
            <div class="col">
                <h2 class="page-title">Quản lý sản phẩm</h2>
                <div class="text-muted">Danh sách sản phẩm</div>
            </div>
            <div class="col-auto ms-auto">
                <a href="/admin/products/edit" class="btn btn-primary">Tạo sản phẩm</a>
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
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Kho</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)):
                            foreach ($products as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['id']); ?></td>
                                    <td style="width:80px">
                                        <?php if (!empty($p['image_url'])): ?>
                                            <img src="<?php echo '../' . htmlspecialchars($p['image_url']); ?>" alt=""
                                                style="width:64px;height:64px;object-fit:cover;border-radius:6px">
                                        <?php else: ?>
                                            <div class="text-muted">No image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                                    <td><?php echo number_format((float) $p['price']); ?></td>
                                    <td><?php echo htmlspecialchars($p['category'] ?? ''); ?></td>
                                    <td><?php echo (int) $p['stock']; ?></td>
                                    <td><?php echo $p['is_active'] ? 'Active' : 'Inactive'; ?></td>
                                    <td class="text-end">
                                        <a href="/admin/products/edit?id=<?php echo urlencode($p['id']); ?>"
                                            class="btn btn-sm btn-outline-secondary">Sửa</a>
                                        <form method="POST" action="/admin/products/delete" style="display:inline-block"
                                            onsubmit="return confirm('Xóa sản phẩm?');">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                            <button class="btn btn-sm btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">Không có sản phẩm</td>
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