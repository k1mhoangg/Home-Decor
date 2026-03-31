<?php
// Lấy current user từ session (nếu chưa có biến $currentUser)
if (!isset($currentUser)) {
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $currentUser = $_SESSION['user']['username'] ?? 'Admin';
}
?>
<!-- Admin top navbar (Tabler) -->
<header class="navbar navbar-expand-md d-print-none"
    style="position: sticky; top: 0; z-index: 999; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div class="container-xl">
        <div class="ms-auto d-flex align-items-center gap-2">
            <form class="d-none d-md-flex" action="/admin/products/search" method="get" style="max-width:300px;">
                <div class="input-icon">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>" style="color:#222">
                </div>
                <button type="submit" class="btn btn-dark ms-2">Tìm kiếm</button>
            </form>

            <div class="dropdown">
                <a href="#" class="btn btn-outline-secondary d-flex align-items-center" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="me-2"><?php echo htmlspecialchars($currentUser); ?></span>
                    <span class="avatar avatar-sm"
                        style="background:#666;color:#fff;border-radius:6px;padding:6px 8px;"><?php echo strtoupper(substr($currentUser, 0, 1)); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="/admin/profile">Hồ sơ</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="/admin/logout">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
</header>