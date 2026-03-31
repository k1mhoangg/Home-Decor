<?php require __DIR__ . '/partial/header.php'; ?>

<?php
if (class_exists(\Core\Session::class))
    $flash = \Core\Session::getFlash();
else {
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $flash = $_SESSION['flash'] ?? null;
    if ($flash)
        unset($_SESSION['flash']);
}

$settings = $settings ?? [];
$decorImages = \Model\About::getDecorImages();
$advantages = \Model\About::getAdvantages();

// Get active tab from query string
$activeTab = isset($_GET['tab']) ? sanitizeInput($_GET['tab']) : 'settings';
$tabMap = ['settings' => 'tab-settings', 'decor-images' => 'tab-decor-images', 'advantages' => 'tab-advantages'];
$activeTabId = $tabMap[$activeTab] ?? 'tab-settings';
?>

<?php require __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 page-header">
            <div class="col">
                <h2 class="page-title">Quản lý trang Giới thiệu</h2>
                <div class="text-muted">Cập nhật nội dung, hình ảnh cho trang About</div>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (!extension_loaded('gd')): ?>
            <div class="alert alert-warning">
                <strong>Cảnh báo:</strong> PHP GD extension chưa được bật. Ảnh upload sẽ được lưu ở kích thước gốc (không resize).
                Vui lòng bật GD extension để tự động resize ảnh.
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tab-settings" class="nav-link <?php echo $activeTabId === 'tab-settings' ? 'active' : ''; ?>" data-bs-toggle="tab">Cài đặt chung</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-decor-images" class="nav-link <?php echo $activeTabId === 'tab-decor-images' ? 'active' : ''; ?>" data-bs-toggle="tab">Ảnh nội thất trang trí</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-advantages" class="nav-link <?php echo $activeTabId === 'tab-advantages' ? 'active' : ''; ?>" data-bs-toggle="tab">Ưu thế nổi bật</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <?php require __DIR__ . '/about_settings.php'; ?>
                    <?php require __DIR__ . '/about_decor_list.php'; ?>
                    <?php require __DIR__ . '/about_advantages_list.php'; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="/public/js/about.js"></script>

<?php require __DIR__ . '/partial/footer.php'; ?>
