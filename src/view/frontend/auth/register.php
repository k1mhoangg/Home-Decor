<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Đăng ký</title>
    <!-- External stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php include BASE_PATH . 'components/header.php'; ?>

    <main class="max-w-md mx-auto mt-8 bg-white p-6 rounded shadow mb-36">
        <h1 class="text-xl font-semibold mb-4">Đăng ký tài khoản</h1>

        <?php
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!empty($_SESSION['flash'])) {
            $f = $_SESSION['flash'];
            $cls = ($f['type'] === 'success') ? 'text-green-700 bg-green-50 border border-green-200 p-3 rounded' : 'text-red-700 bg-red-50 border border-red-200 p-3 rounded';
            echo '<div class="' . $cls . ' mb-3">' . htmlspecialchars($f['message']) . '</div>';
            unset($_SESSION['flash']);
        }
        ?>

        <form method="POST" action="/register">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">
                    Tên đăng nhập <span class="text-red-500">*</span>
                </label>
                <input name="username" type="text"
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:border-blue-500"
                    placeholder="Nhập tên đăng nhập" required />
                <small class="text-gray-500 text-xs">Tên đăng nhập dùng để đăng nhập vào hệ thống</small>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input name="email" type="email"
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:border-blue-500"
                    placeholder="Nhập địa chỉ email" required />
                <small class="text-gray-500 text-xs">Email dùng để nhận thông báo và khôi phục mật khẩu</small>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tên hiển thị</label>
                <input name="display_name" type="text"
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:border-blue-500"
                    placeholder="Nhập tên hiển thị (tùy chọn)" />
                <small class="text-gray-500 text-xs">Tên này sẽ hiển thị trên website</small>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">
                    Mật khẩu <span class="text-red-500">*</span>
                </label>
                <input name="password" type="password"
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:border-blue-500"
                    placeholder="Nhập mật khẩu" required minlength="6" />
                <small class="text-gray-500 text-xs">Mật khẩu phải có ít nhất 6 ký tự</small>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Xác nhận mật khẩu <span class="text-red-500">*</span>
                </label>
                <input name="password_confirm" type="password"
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:border-blue-500"
                    placeholder="Nhập lại mật khẩu" required minlength="6" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 transition">
                    Đăng ký
                </button>
                <a href="/login" class="text-sm text-blue-600 hover:underline">
                    Đã có tài khoản? Đăng nhập
                </a>
            </div>
        </form>
    </main>

    <?php include BASE_PATH . 'components/footer.php'; ?>
</body>

</html>