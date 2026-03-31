<?php include __DIR__ . '/partial/header.php'; ?>

<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
if (!empty($_SESSION['flash'])) {
    $f = $_SESSION['flash'];
    echo '<div class="' . (($f['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger') . '">' . htmlspecialchars($f['message']) . '</div>';
    unset($_SESSION['flash']);
}

$id = $product['id'] ?? $product['id'] ?? null;
$name = $product['name'] ?? '';
$description = $product['description'] ?? '';
$price = $product['price'] ?? '';
$discount_price = $product['discount_price'] ?? '';
$category = $product['category'] ?? '';
$stock = $product['stock'] ?? 0;
$image_url = $product['image_url'] ?? '';
$is_featured = !empty($product['is_featured']);
$is_active = isset($product['is_active']) ? (bool) $product['is_active'] : true;
?>

<?php include __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 page-header">
            <div class="col">
                <h2 class="page-title"><?php echo $id ? 'Chỉnh sửa sản phẩm' : 'Tạo sản phẩm mới'; ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="/admin/products" class="btn btn-outline-secondary">Quay lại</a>
            </div>
        </div>

        <form method="POST" action="/admin/products/save" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="6"
                    class="form-control"><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="row">
                <div class="col">
                    <label class="form-label">Giá</label>
                    <input name="price" type="number" step="0.01" value="<?php echo htmlspecialchars($price); ?>"
                        class="form-control" required />
                </div>
                <div class="col">
                    <label class="form-label">Giá giảm</label>
                    <input name="discount_price" type="number" step="0.01"
                        value="<?php echo htmlspecialchars($discount_price); ?>" class="form-control" />
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Danh mục</label>
                <input name="category" value="<?php echo htmlspecialchars($category); ?>" class="form-control" />
            </div>

            <div class="mb-3">
                <label class="form-label">Kho</label>
                <input name="stock" type="number" value="<?php echo htmlspecialchars($stock); ?>"
                    class="form-control" />
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh sản phẩm</label>
                <?php if (!empty($image_url)): ?>
                    <div class="mb-2"><img src="<?php echo htmlspecialchars($image_url); ?>"
                            style="width:120px;height:80px;object-fit:cover;border-radius:6px"></div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*" class="form-control" />
            </div>

            <div class="mb-3 form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="is_featured" class="form-check-input" <?php echo $is_featured ? 'checked' : ''; ?>> Nổi bật
                </label>
            </div>

            <div class="mb-3 form-check">
                <label class="form-check-label">
                    <input type="checkbox" name="is_active" class="form-check-input" <?php echo $is_active ? 'checked' : ''; ?>> Kích hoạt
                </label>
            </div>

            <div>
                <button class="btn btn-primary"><?php echo $id ? 'Lưu' : 'Tạo mới'; ?></button>
            </div>
        </form>

    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>