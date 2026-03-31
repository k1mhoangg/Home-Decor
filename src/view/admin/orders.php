<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý đơn hàng - HomeDecor Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body class="antialiased">
    <?php require BASE_PATH . '/view/admin/partial/header.php'; ?>
    <div class="page-wrapper">
        <div class="container-xl py-4">
            <h1 class="mb-4 fw-bold fs-2">Quản lý đơn hàng</h1>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($flash)): ?>
                            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert-danger'; ?>">
                                <?php echo htmlspecialchars($flash['message']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($orders)): foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td class="text-success fw-bold"><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</td>
                            <td>
                                <?php if ($order['status'] === 'da_thanh_toan'): ?>
                                    <span class="badge bg-success">Đã thanh toán</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Chưa thanh toán</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="/admin/orders/view?id=<?php echo (int)$order['id']; ?>" class="btn btn-sm btn-primary"><i class="ti ti-eye"></i> Xem</a>
                                <form method="post" action="/admin/orders/delete" class="d-inline-block ms-2" style="display:inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                                    <input type="hidden" name="id" value="<?php echo (int)$order['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center text-muted">Không có đơn hàng nào.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
            <?php if (isset($pagination)) include BASE_PATH . '/view/admin/partial/pagination.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
</body>
</html>
