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

// Get active tab from query string
$activeTab = isset($_GET['tab']) ? sanitizeInput($_GET['tab']) : 'published';
$tabMap = ['published' => 'tab-published', 'unpublished' => 'tab-unpublished', 'unanswered' => 'tab-unanswered'];
$activeTabId = $tabMap[$activeTab] ?? 'tab-published';
$search = $search ?? (isset($_GET['search']) ? trim($_GET['search']) : '');
?>

<?php require __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 page-header">
            <div class="col">
                <h2 class="page-title">Quản lý Hỏi/Đáp</h2>
                <div class="text-muted">Quản lý các câu hỏi và câu trả lời</div>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a href="/admin/faqs?tab=published" class="nav-link <?php echo $activeTabId === 'tab-published' ? 'active' : ''; ?>">Đã hiển thị</a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/faqs?tab=unpublished" class="nav-link <?php echo $activeTabId === 'tab-unpublished' ? 'active' : ''; ?>">Chưa hiển thị</a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/faqs?tab=unanswered" class="nav-link <?php echo $activeTabId === 'tab-unanswered' ? 'active' : ''; ?>">Chưa trả lời</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <?php require __DIR__ . '/faq_published.php'; ?>
                    <?php require __DIR__ . '/faq_unpublished.php'; ?>
                    <?php require __DIR__ . '/faq_unanswered.php'; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/partial/footer.php'; ?>

