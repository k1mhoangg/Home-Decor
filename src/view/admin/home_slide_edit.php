<?php include __DIR__ . '/partial/header.php'; ?>
<?php include __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col">
                <h2 class="page-title"><?php echo $isEdit ? 'Sửa' : 'Thêm'; ?> Slide</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/home-settings/slides/save">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?php echo $slide['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label required">Chọn sản phẩm (lấy ảnh từ sản phẩm)</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php foreach ($allProducts as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo ($slide['product_id'] ?? 0) == $p['id'] ? 'selected' : ''; ?>>
                                    #<?php echo $p['id']; ?> - <?php echo htmlspecialchars($p['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-hint">Ảnh sẽ được lấy từ sản phẩm đã chọn</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control"
                            value="<?php echo htmlspecialchars($slide['title'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phụ đề</label>
                        <input type="text" name="subtitle" class="form-control"
                            value="<?php echo htmlspecialchars($slide['subtitle'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Text nút bấm</label>
                        <input type="text" name="button_text" class="form-control"
                            value="<?php echo htmlspecialchars($slide['button_text'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link nút bấm</label>
                        <input type="text" name="button_link" class="form-control"
                            value="<?php echo htmlspecialchars($slide['button_link'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="display_order" class="form-control"
                            value="<?php echo (int) ($slide['display_order'] ?? 0); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" <?php echo ($slide['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <span class="form-check-label">Kích hoạt</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>Lưu
                        </button>
                        <a href="/admin/home-settings?tab=slides" class="btn btn-link">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>