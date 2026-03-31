<?php
// $product phải được truyền vào từ controller
?>
<?php if (!empty($product)): ?>
<div class="product-detail-card">
    
    <div>
        <img src="<?= htmlspecialchars('/' . ltrim($product['image_url'], '/')) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-48 object-cover rounded-md mb-2 transition-transform hover:scale-105" loading="lazy">    
    </div>
        <div class="product-detail-info">
        <h1 class="product-detail-title"><?= htmlspecialchars($product['name']) ?></h1>
        <div class="product-detail-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
        <div>
            <?php if (!empty($product['discount_price']) && $product['discount_price'] > 0): ?>
                <span class="product-detail-price">
                    <?= number_format($product['discount_price'], 0, ',', '.') ?>₫
                </span>
                <span class="product-detail-oldprice">
                    <?= number_format($product['price'], 0, ',', '.') ?>₫
                </span>
            <?php else: ?>
                <span class="product-detail-price">
                    <?= number_format($product['price'], 0, ',', '.') ?>₫
                </span>
            <?php endif; ?>
        </div>
        <div class="product-detail-meta">Danh mục: <?= htmlspecialchars($product['category']) ?></div>
        <div class="product-detail-meta">Kho: <?= (int)$product['stock'] ?> sản phẩm</div>
        <form method="post" action="/cart/add" class="product-detail-form">
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <input type="number" name="quantity" value="1" min="1" max="<?= (int)$product['stock'] ?>">
            <button type="submit" class="buy-btn">Thêm vào giỏ hàng</button>
        </form>
    </div>
</div>
<?php else: ?>
    <div class="text-center text-red-500 text-xl font-semibold py-10">Sản phẩm không tồn tại!</div>
<?php endif; ?>
