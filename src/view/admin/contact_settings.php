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
        <div class="row mb-3 g-2 align-items-center page-header">
            <div class="col">
                <h2 class="page-title">Quản lý trang Liên hệ</h2>
                <div class="text-muted">Chỉnh sửa nội dung hiển thị trên trang liên hệ</div>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div
                class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?> alert-dismissible">
                <?php echo htmlspecialchars($flash['message']); ?>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        <?php endif; ?>

        <form method="POST" action="/admin/contact-settings/save">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Company Info -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin công ty</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Tên công ty</label>
                                <input type="text" name="company_name" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['company_name'] ?? ''); ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tên viết tắt</label>
                                    <input type="text" name="company_short_name" class="form-control"
                                        value="<?php echo htmlspecialchars($settings['company_short_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Slogan</label>
                                    <input type="text" name="company_slogan" class="form-control"
                                        value="<?php echo htmlspecialchars($settings['company_slogan'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Intro Content -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Nội dung giới thiệu</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Giới thiệu công ty</label>
                                <small class="form-hint d-block mb-2 text-muted">Mỗi đoạn văn cách nhau bằng 1 dòng
                                    trống</small>
                                <textarea name="intro_content" rows="10"
                                    class="form-control"><?php echo htmlspecialchars($settings['intro_content'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Activities -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Hoạt động chính</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Danh sách hoạt động</label>
                                <small class="form-hint d-block mb-2 text-muted">Mỗi hoạt động trên 1 dòng</small>
                                <textarea name="activities_content" rows="8"
                                    class="form-control"><?php echo htmlspecialchars($settings['activities_content'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Dịch vụ khác</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Danh sách dịch vụ</label>
                                <small class="form-hint d-block mb-2 text-muted">Mỗi dịch vụ trên 1 dòng</small>
                                <textarea name="services_content" rows="8"
                                    class="form-control"><?php echo htmlspecialchars($settings['services_content'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Map Embed -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Bản đồ Google Maps</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ hiển thị trên bản đồ</label>
                                <small class="form-hint d-block mb-2 text-muted">
                                    Nhập địa chỉ đầy đủ, hệ thống sẽ tự động tạo bản đồ Google Maps.<br>
                                    Ví dụ: "268 Lý Thường Kiệt, Quận 10, TP. Hồ Chí Minh"
                                </small>
                                <input type="text" name="map_address" id="map_address" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['map_address'] ?? $settings['address'] ?? ''); ?>"
                                    placeholder="Nhập địa chỉ để hiển thị trên bản đồ">
                            </div>

                            <!-- Preview Map -->
                            <div class="mb-3">
                                <label class="form-label">Xem trước bản đồ</label>
                                <div id="map_preview" class="border rounded"
                                    style="height: 300px; background: #f5f5f5;">
                                    <?php
                                    $mapAddress = $settings['map_address'] ?? $settings['address'] ?? '';
                                    if (!empty($mapAddress)):
                                        $encodedAddress = urlencode($mapAddress);
                                        ?>
                                        <iframe
                                            src="https://maps.google.com/maps?q=<?php echo $encodedAddress; ?>&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                            width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            <span>Nhập địa chỉ để xem bản đồ</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" id="preview_map_btn" class="btn btn-outline-secondary mt-2">
                                    <i class="ti ti-refresh me-1"></i>Cập nhật xem trước
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- Contact Info -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin liên hệ</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Hotline 1</label>
                                <input type="text" name="hotline" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['hotline'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hotline 2</label>
                                <input type="text" name="hotline_2" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['hotline_2'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <textarea name="address" rows="3"
                                    class="form-control"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giờ làm việc</label>
                                <input type="text" name="working_hours" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['working_hours'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Mạng xã hội</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Facebook URL</label>
                                <input type="url" name="facebook_url" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Google URL</label>
                                <input type="url" name="google_url" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['google_url'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pinterest URL</label>
                                <input type="url" name="pinterest_url" class="form-control"
                                    value="<?php echo htmlspecialchars($settings['pinterest_url'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-device-floppy me-2"></i>Lưu cài đặt
                            </button>
                            <a href="/contact" target="_blank" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="ti ti-external-link me-2"></i>Xem trang liên hệ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapAddressInput = document.getElementById('map_address');
        const mapPreview = document.getElementById('map_preview');
        const previewBtn = document.getElementById('preview_map_btn');

        function updateMapPreview() {
            const address = mapAddressInput.value.trim();
            if (address) {
                const encodedAddress = encodeURIComponent(address);
                mapPreview.innerHTML = `<iframe 
                src="https://maps.google.com/maps?q=${encodedAddress}&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                width="100%" 
                height="300" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>`;
            } else {
                mapPreview.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted"><span>Nhập địa chỉ để xem bản đồ</span></div>';
            }
        }

        previewBtn.addEventListener('click', updateMapPreview);

        // Auto update on Enter key
        mapAddressInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                updateMapPreview();
            }
        });
    });
</script>

<?php include __DIR__ . '/partial/footer.php'; ?>