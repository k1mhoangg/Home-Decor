<?php
// $items được truyền từ controller
?>
<?php if (empty($items)): ?>
    <div class="text-center text-gray-500 text-lg py-10">Giỏ hàng của bạn đang trống.</div>
<?php else: ?>
    <div class="overflow-x-auto">
        <form method="post" action="/cart/checkout">
            <table class="min-w-full bg-white rounded-lg shadow cart-table">
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
                    <?php $total = 0; foreach ($items as $item): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-16 h-16 object-cover rounded"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>
                            <?php if (!empty($item['discount_price']) && $item['discount_price'] > 0): ?>
                                <span class="text-green-600 font-bold">
                                    <?= number_format($item['discount_price'], 0, ',', '.') ?>₫
                                </span>
                            <?php else: ?>
                                <span class="text-green-600 font-bold">
                                    <?= number_format($item['price'], 0, ',', '.') ?>₫
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?= (int)$item['quantity'] ?></td>
                        <td>
                            <?php $itemTotal = ($item['discount_price'] > 0 ? $item['discount_price'] : $item['price']) * $item['quantity']; $total += $itemTotal; ?>
                            <span class="font-semibold">
                                <?= number_format($itemTotal, 0, ',', '.') ?>₫
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right font-bold">Tổng cộng:</td>
                        <td class="font-bold text-green-700 text-lg">
                            <?= number_format($total, 0, ',', '.') ?>₫
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="flex justify-end mt-6">
                <button type="submit" class="buy-btn px-6 py-2">Xác Nhận</button>
            </div>
        </form>
    </div>
<?php endif; ?>
