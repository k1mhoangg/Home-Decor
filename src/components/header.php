<!-- components/header.php -->
<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();
$current_user = $_SESSION['user'] ?? null;
$current_uri = $_SERVER['REQUEST_URI'];
$current_path = parse_url($current_uri, PHP_URL_PATH);
?>
<header class="header">
  <div class="container">
    <div class="logo flex items-center gap-2">
      <img src="/favicon.ico" alt="HomeDecor Logo" class="w-5 h-5 object-contain">
      <span class="font-bold text-yellow-400">HomeDecor</span>
    </div>

    <!-- Desktop & Tablet: Navigation -->
    <nav class="main-nav">
      <a href="/" class="<?php echo ($current_path === '/' || $current_path === '') ? 'active' : ''; ?>">Trang chủ</a>
      <a href="/about" class="<?php echo ($current_path === '/about') ? 'active' : ''; ?>">Giới thiệu</a>
      <a href="/pricing" class="<?php echo ($current_path === '/pricing') ? 'active' : ''; ?>">Bảng giá</a>
      <a href="/contact" class="<?php echo ($current_path === '/contact') ? 'active' : ''; ?>">Liên hệ</a>
      <a href="/faq" class="<?php echo ($current_path === '/faq') ? 'active' : ''; ?>">Hỏi đáp</a>
      <a href="/news" class="<?php echo ($current_path === '/news') ? 'active' : ''; ?>">Tin tức</a>
    </nav>

    <!-- Desktop: Others (search, user, cart) -->
    <div class="others">
      <form action="/search" method="get" style="display:inline-block;">
        <input type="text" name="q" placeholder="Tìm kiếm..." style="color:#111;" />
        <button type="submit" style="background:none;border:none;padding:0;margin-left:2px;cursor:pointer;"><i
            class="fas fa-search"></i></button>
      </form>
      <?php if ($current_user): ?>
        <a href="/profile"><i class="fa-regular fa-user text-white"></i></a>
        <a href="/logout" class="text-white"><i class="fa-solid fa-right-from-bracket"></i></a>
      <?php else: ?>
        <a href="/login"><i class="fa-regular fa-user text-white"></i></a>
        <a href="/register" class="ml-2 text-white">Đăng ký</a>
      <?php endif; ?>
      <a href="/cart"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>

    <!-- Hamburger Menu Button (Tablet & Mobile) -->
    <button class="hamburger-btn" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <!-- Mobile/Tablet Menu (contains nav and/or others based on screen size) -->
    <div class="mobile-menu">
      <!-- Mobile: Navigation -->
      <nav class="mobile-nav">
        <a href="/" class="<?php echo ($current_path === '/' || $current_path === '') ? 'active' : ''; ?>">Trang chủ</a>
        <a href="/about" class="<?php echo ($current_path === '/about') ? 'active' : ''; ?>">Giới thiệu</a>
        <a href="/pricing" class="<?php echo ($current_path === '/pricing') ? 'active' : ''; ?>">Bảng giá</a>
        <a href="/contact" class="<?php echo ($current_path === '/contact') ? 'active' : ''; ?>">Liên hệ</a>
        <a href="/faq" class="<?php echo ($current_path === '/faq') ? 'active' : ''; ?>">Hỏi đáp</a>
        <a href="/news" class="<?php echo ($current_path === '/news') ? 'active' : ''; ?>">Tin tức</a>
      </nav>

      <!-- Tablet/Mobile: Others -->
      <div class="mobile-others">
        <form action="/search" method="get" class="mobile-search-form">
          <input type="text" name="q" placeholder="Tìm kiếm..." />
          <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <div class="mobile-actions">
          <?php if ($current_user): ?>
            <span class="mobile-greeting"><?php echo htmlspecialchars($current_user['username']); ?></span>
            <a href="/profile" title="Hồ sơ"><i class="fa-regular fa-user"></i></a>
            <a href="/logout" title="Đăng xuất"><i class="fa-solid fa-right-from-bracket"></i></a>
          <?php else: ?>
            <a href="/login" title="Đăng nhập"><i class="fa-regular fa-user"></i></a>
            <a href="/register" title="Đăng ký">Đăng ký</a>
          <?php endif; ?>
          <a href="/cart" title="Giỏ hàng"><i class="fa-solid fa-cart-shopping"></i></a>
        </div>
      </div>
    </div>
  </div>
</header>