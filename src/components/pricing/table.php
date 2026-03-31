<?php
// Component: Bảng giá sản phẩm
// $products được truyền từ controller, lấy từ database
?>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
  <?php foreach ($products as $product): ?>
    <div class="bg-white rounded-lg shadow p-4 flex flex-col h-full">
      <a href="/product/<?= htmlspecialchars($product['id']) ?>" class="block mb-3">
        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded-md mb-2 transition-transform hover:scale-105" loading="lazy">
        <h2 class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition-colors line-clamp-2"><?= htmlspecialchars($product['name']) ?></h2>
      </a>
      <div class="text-gray-600 text-sm flex-1 mb-2 line-clamp-3"><?= htmlspecialchars($product['description']) ?></div>
      <div class="flex items-center justify-between mt-auto">
        <div>
          <?php if (!empty($product['discount_price']) && $product['discount_price'] > 0): ?>
            <span class="text-green-600 font-bold text-base mr-2">
              <?= number_format($product['discount_price'], 0, ',', '.') ?>₫
            </span>
            <span class="line-through text-gray-400 text-sm">
              <?= number_format($product['price'], 0, ',', '.') ?>₫
            </span>
          <?php else: ?>
            <span class="text-green-600 font-bold text-base">
              <?= number_format($product['price'], 0, ',', '.') ?>₫
            </span>
          <?php endif; ?>
        </div>
        <form method="post" action="/cart/add" class="inline">
          <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="buy-btn ml-2">Thêm vào giỏ</button>
        </form>
      </div>
      <a href="/product/<?= htmlspecialchars($product['id']) ?>" class="mt-3 text-blue-500 hover:underline text-sm text-center">Xem chi tiết</a>
    </div>
  <?php endforeach; ?>
</div>
