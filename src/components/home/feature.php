<?php
$showFeatured = $homeSettings['show_featured'] ?? true;
$showRecent = $homeSettings['show_recent'] ?? true;
$featuredTitle = $homeSettings['featured_title'] ?? 'SẢN PHẨM NỔI BẬT';
$recentTitle = $homeSettings['recent_title'] ?? 'SẢN PHẨM MỚI NHẤT';
?>

<?php if ($showFeatured && !empty($featuredProducts)): ?>
    <div class="w-[90%] mx-auto bg-white mt-8 p-4 shadow-md rounded-lg">
        <h2 class="text-center text-xl font-semibold mb-4 border-b pb-2"><?php echo htmlspecialchars($featuredTitle); ?>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="flex flex-col items-center">
                    <a href="/product/<?php echo htmlspecialchars($product['id']); ?>">
                        <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'images/sample.jpg'); ?>"
                            class="w-full h-24 object-cover rounded-md mb-2"
                            alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </a>
                    <p class="text-xs text-center font-medium uppercase line-clamp-2">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </p>
                    <p class="text-xs text-center text-gray-600 mt-1"><?php echo number_format($product['price']); ?>₫</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($showRecent && !empty($recentProducts)): ?>
    <div class="w-[90%] mx-auto bg-white mt-10 p-4 shadow-md rounded-lg mb-10">
        <h2 class="text-center text-xl font-semibold mb-4 border-b pb-2"><?php echo htmlspecialchars($recentTitle); ?></h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($recentProducts as $product): ?>
                <div class="flex flex-col bg-gray-100 rounded-md shadow-sm overflow-hidden">
                    <a href="/product/<?php echo htmlspecialchars($product['id']); ?>">
                        <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'images/sample.jpg'); ?>"
                            class="h-40 w-full object-cover" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </a>
                    <div class="p-3 flex flex-col gap-2">
                        <span
                            class="text-xs text-gray-500"><?php echo htmlspecialchars($product['category'] ?? 'Nội thất'); ?></span>
                        <h3 class="font-semibold text-sm line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="text-xs text-gray-600 line-clamp-2">
                            <?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 80)); ?>...
                        </p>
                        <div class="flex justify-between items-center mt-2">
                            <span
                                class="text-sm font-bold text-blue-600"><?php echo number_format($product['price']); ?>₫</span>
                            <?php if (!empty($product['discount_price'])): ?>
                                <span
                                    class="text-xs text-gray-400 line-through"><?php echo number_format($product['discount_price']); ?>₫</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>