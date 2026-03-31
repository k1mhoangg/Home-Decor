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

$tabMap = ['settings' => 'tab-settings', 'slides' => 'tab-slides', 'banners' => 'tab-banners'];
$activeTabId = $tabMap[$activeTab] ?? 'tab-settings';
?>

<?php include __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 page-header">
            <div class="col">
                <h2 class="page-title">Quản lý trang chủ</h2>
                <div class="text-muted">Cấu hình nội dung hiển thị trên trang chủ</div>
            </div>
            <div class="col-auto">
                <a href="/" target="_blank" class="btn btn-outline-secondary">
                    <i class="ti ti-external-link me-1"></i>Xem trang chủ
                </a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div
                class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?> alert-dismissible">
                <?php echo htmlspecialchars($flash['message']); ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tab-settings"
                            class="nav-link <?php echo $activeTabId === 'tab-settings' ? 'active' : ''; ?>"
                            data-bs-toggle="tab">Cài đặt chung</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-slides"
                            class="nav-link <?php echo $activeTabId === 'tab-slides' ? 'active' : ''; ?>"
                            data-bs-toggle="tab">Carousel Slides</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-banners"
                            class="nav-link <?php echo $activeTabId === 'tab-banners' ? 'active' : ''; ?>"
                            data-bs-toggle="tab">Banner Panels</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Settings Tab -->
                    <div class="tab-pane <?php echo $activeTabId === 'tab-settings' ? 'active show' : ''; ?>"
                        id="tab-settings">
                        <form method="POST" action="/admin/home-settings/save">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="mb-3">Carousel</h4>
                                    <div class="mb-3">
                                        <label class="form-check">
                                            <input type="checkbox" name="show_carousel" class="form-check-input" <?php echo ($settings['show_carousel'] ?? 1) ? 'checked' : ''; ?>>
                                            <span class="form-check-label">Hiển thị Carousel</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="mb-3">Banner Section</h4>
                                    <div class="mb-3">
                                        <label class="form-check">
                                            <input type="checkbox" name="show_banner" class="form-check-input" <?php echo ($settings['show_banner'] ?? 1) ? 'checked' : ''; ?>>
                                            <span class="form-check-label">Hiển thị Banner</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="mb-3">Phần Sản phẩm nổi bật</h4>
                                    <div class="mb-3">
                                        <label class="form-check">
                                            <input type="checkbox" name="show_featured" class="form-check-input" <?php echo ($settings['show_featured'] ?? 1) ? 'checked' : ''; ?>>
                                            <span class="form-check-label">Hiển thị</span>
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề</label>
                                        <input type="text" name="featured_title" class="form-control"
                                            value="<?php echo htmlspecialchars($settings['featured_title'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Chọn sản phẩm xuất hiện (Featured) — chọn
                                            nhiều</label>
                                        <small class="form-hint d-block mb-2 text-muted">Nếu chọn sản phẩm ở đây, hệ
                                            thống sẽ ưu tiên hiển thị các sản phẩm này theo thứ tự. Giữ Ctrl/Cmd để chọn
                                            nhiều.</small>
                                        <select name="featured_product_ids[]" class="form-select" multiple size="8">
                                            <?php
                                            $selectedFeatured = \Model\HomeSettings::parseSelectedIds($settings['featured_product_ids'] ?? '');
                                            foreach ($allProducts as $ap): ?>
                                                <option value="<?php echo htmlspecialchars($ap['id']); ?>" <?php echo in_array((int) $ap['id'], $selectedFeatured) ? 'selected' : ''; ?>>
                                                    #<?php echo htmlspecialchars($ap['id']); ?> —
                                                    <?php echo htmlspecialchars($ap['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h4 class="mb-3">Phần Sản phẩm mới</h4>
                                    <div class="mb-3">
                                        <label class="form-check">
                                            <input type="checkbox" name="show_recent" class="form-check-input" <?php echo ($settings['show_recent'] ?? 1) ? 'checked' : ''; ?>>
                                            <span class="form-check-label">Hiển thị</span>
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề</label>
                                        <input type="text" name="recent_title" class="form-control"
                                            value="<?php echo htmlspecialchars($settings['recent_title'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Chọn sản phẩm xuất hiện (Recent) — chọn nhiều</label>
                                        <small class="form-hint d-block mb-2 text-muted">Nếu chọn sản phẩm ở đây, hệ
                                            thống sẽ ưu tiên hiển thị các sản phẩm này theo thứ tự. Giữ Ctrl/Cmd để chọn
                                            nhiều.</small>
                                        <select name="recent_product_ids[]" class="form-select" multiple size="8">
                                            <?php
                                            $selectedRecent = \Model\HomeSettings::parseSelectedIds($settings['recent_product_ids'] ?? '');
                                            foreach ($allProducts as $ap): ?>
                                                <option value="<?php echo htmlspecialchars($ap['id']); ?>" <?php echo in_array((int) $ap['id'], $selectedRecent) ? 'selected' : ''; ?>>
                                                    #<?php echo htmlspecialchars($ap['id']); ?> —
                                                    <?php echo htmlspecialchars($ap['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i>Lưu cài đặt
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Slides Tab -->
                    <div class="tab-pane <?php echo $activeTabId === 'tab-slides' ? 'active show' : ''; ?>"
                        id="tab-slides">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Carousel Slides</h4>
                            <a href="/admin/home-settings/slides/create" class="btn btn-primary btn-sm">
                                <i class="ti ti-plus me-1"></i>Thêm slide
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th style="width:80px">Ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Thứ tự</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($slides)):
                                        foreach ($slides as $s): ?>
                                            <tr>
                                                <td><img src="<?php echo '../' . htmlspecialchars($s['image_url']); ?>"
                                                        style="width:60px;height:40px;object-fit:cover;border-radius:4px"></td>
                                                <td><?php echo htmlspecialchars($s['title'] ?? '—'); ?></td>
                                                <td><?php echo (int) $s['display_order']; ?></td>
                                                <td><span
                                                        class="badge <?php echo $s['is_active'] ? 'bg-green' : 'bg-secondary'; ?>"><?php echo $s['is_active'] ? 'Active' : 'Inactive'; ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="/admin/home-settings/slides/edit?id=<?php echo $s['id']; ?>"
                                                        class="btn btn-sm btn-outline-secondary">Sửa</a>
                                                    <form method="POST" action="/admin/home-settings/slides/delete"
                                                        style="display:inline" onsubmit="return confirm('Xóa slide?')">
                                                        <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                                        <button class="btn btn-sm btn-danger">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Chưa có slide</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Banners Tab -->
                    <div class="tab-pane <?php echo $activeTabId === 'tab-banners' ? 'active show' : ''; ?>"
                        id="tab-banners">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Banner Panels</h4>
                            <a href="/admin/home-settings/banners/create" class="btn btn-primary btn-sm">
                                <i class="ti ti-plus me-1"></i>Thêm banner
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th style="width:80px">Ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Link</th>
                                        <th>Thứ tự</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($banners)):
                                        foreach ($banners as $b): ?>
                                            <tr>
                                                <td><img src="<?php echo '../' . htmlspecialchars($b['image_url']); ?>"
                                                        style="width:60px;height:40px;object-fit:cover;border-radius:4px"></td>
                                                <td><?php echo htmlspecialchars($b['title'] ?? '—'); ?></td>
                                                <td><?php echo htmlspecialchars($b['link'] ?? '/'); ?></td>
                                                <td><?php echo (int) $b['display_order']; ?></td>
                                                <td><span
                                                        class="badge <?php echo $b['is_active'] ? 'bg-green' : 'bg-secondary'; ?>"><?php echo $b['is_active'] ? 'Active' : 'Inactive'; ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="/admin/home-settings/banners/edit?id=<?php echo $b['id']; ?>"
                                                        class="btn btn-sm btn-outline-secondary">Sửa</a>
                                                    <form method="POST" action="/admin/home-settings/banners/delete"
                                                        style="display:inline" onsubmit="return confirm('Xóa banner?')">
                                                        <input type="hidden" name="id" value="<?php echo $b['id']; ?>">
                                                        <button class="btn btn-sm btn-danger">Xóa</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Chưa có banner</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>