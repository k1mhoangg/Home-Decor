<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login - HomeDecor</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
</head>

<body class="antialiased">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="/" class="d-inline-flex align-items-center gap-2 text-decoration-none">
                    <img src="/favicon.ico" height="48" width="48" alt="HomeDecor">
                    <span class="fs-3 fw-bold text-dark">HomeDecor Admin</span>
                </a>
            </div>

            <form class="card card-md" method="POST" action="/admin/login">
                <div class="card-body">
                    <h2 class="card-title text-center mb-3">Đăng nhập quản trị</h2>

                    <?php
                    if (session_status() === PHP_SESSION_NONE)
                        session_start();
                    if (!empty($_SESSION['flash'])) {
                        $f = $_SESSION['flash'];
                        $cls = ($f['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger';
                        echo '<div class="' . $cls . '">' . htmlspecialchars($f['message']) . '</div>';
                        unset($_SESSION['flash']);
                    }
                    ?>

                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control" placeholder="Tài khoản" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                    </div>
                </div>
            </form>

            <div class="text-center text-muted mt-3">
                <a href="/">Quay về trang chủ</a>
            </div>
        </div>
    </div>
</body>

</html>