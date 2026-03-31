<?php include __DIR__ . '/partial/header.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
if (!empty($flash)) {
    echo '<div class="' . (($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger') . '">' . htmlspecialchars($flash['message']) . '</div>';
}
?>
<?php include __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 page-header">
            <div class="col">
                <h2 class="page-title">Chi tiết đơn hàng</h2>
                <div class="text-muted">Thông tin đơn hàng #<?php echo htmlspecialchars($order['id'] ?? ''); ?></div>
            </div>
            <div class="col-auto ms-auto">
                <a href="/admin/orders" class="btn btn-outline-secondary">Quay lại</a>
                <?php if (!empty($order)): ?>
                <form method="post" action="/admin/orders/delete" class="d-inline-block ms-2" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                    <input type="hidden" name="id" value="<?php echo (int)$order['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Xóa đơn hàng</button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <?php if ($order): ?>
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Mã đơn hàng</dt>
                        <dd class="col-sm-9">#<?php echo htmlspecialchars($order['id']); ?></dd>
                        <dt class="col-sm-3">Khách hàng</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($order['username']); ?></dd>
                        <dt class="col-sm-3">Tổng tiền</dt>
                        <dd class="col-sm-9"><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</dd>
                        <dt class="col-sm-3">Trạng thái</dt>
                        <dd class="col-sm-9">
                            <?php if ($order['status'] === 'da_thanh_toan'): ?>
                                <span class="badge bg-success">Đã thanh toán</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Chưa thanh toán</span>
                                <form method="post" action="/admin/orders/confirm" class="d-inline-block mt-2">
                                    <input type="hidden" name="id" value="<?php echo (int)$order['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Xác nhận đã thanh toán cho đơn hàng này?');">Xác nhận thanh toán</button>
                                </form>
                            <?php endif; ?>
                        </dd>
                        <dt class="col-sm-3">Ngày tạo</dt>
                        <dd class="col-sm-9"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></dd>
                    </dl>
                <?php else: ?>
                    <div class="alert alert-danger">Đơn hàng không tồn tại!</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>Sản phẩm trong đơn hàng</strong></div>
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): foreach ($items as $item): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width:64px;height:48px;object-fit:cover;border-radius:6px"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo number_format($item['price_each'], 0, ',', '.'); ?>₫</td>
                            <td><?php echo (int)$item['quantity']; ?></td>
                            <td><?php echo number_format($item['total_price'], 0, ',', '.'); ?>₫</td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center text-muted">Không có sản phẩm nào trong đơn hàng này.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>
