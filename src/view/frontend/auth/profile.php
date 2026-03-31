<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Hồ sơ cá nhân</title>
    <!-- External stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php include BASE_PATH . 'components/header.php'; ?>

    <main class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded shadow space-y-6 mb-36">
        <h1 class="text-2xl font-semibold">Hồ sơ cá nhân</h1>

        <?php
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!empty($_SESSION['flash'])) {
            $f = $_SESSION['flash'];
            $cls = ($f['type'] === 'success') ? 'bg-green-100 text-green-800' : (($f['type'] === 'error') ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800');
            echo '<div class="p-3 rounded ' . $cls . '">' . htmlspecialchars($f['message']) . '</div>';
            unset($_SESSION['flash']);
        }
        // $user variable provided by controller
        ?>

        <section>
            <form method="POST" action="/profile" enctype="multipart/form-data" class="grid grid-cols-1 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-24 h-24 bg-gray-200 rounded-full overflow-hidden">
                        <?php if (!empty($user->getAvatar())): ?>
                            <img src="<?php echo htmlspecialchars($user->getAvatar()); ?>" alt="avatar"
                                class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-500">No avatar</div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Đổi avatar</label>
                        <input type="file" name="avatar" accept="image/*">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Tên hiển thị</label>
                    <input name="display_name"
                        value="<?php echo htmlspecialchars($user->getDisplayName() ?? $user->getUsername()); ?>"
                        class="w-full border px-3 py-2 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input name="email" value="<?php echo htmlspecialchars($user->getEmail() ?? ''); ?>"
                        class="w-full border px-3 py-2 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Tên đăng nhập</label>
                    <input name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>"
                        class="w-full border px-3 py-2 rounded" />
                </div>

                <div class="flex gap-3">
                    <button class="bg-gray-800 text-white px-4 py-2 rounded">Lưu thay đổi</button>
                    <a href="/" class="px-4 py-2 border rounded">Hủy</a>
                </div>
            </form>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">Đổi mật khẩu</h2>
            <form method="POST" action="/profile/password" class="grid grid-cols-1 gap-3 max-w-sm">
                <div>
                    <label class="block text-sm font-medium">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="w-full border px-3 py-2 rounded" required />
                </div>
                <div>
                    <label class="block text-sm font-medium">Mật khẩu mới</label>
                    <input type="password" name="new_password" class="w-full border px-3 py-2 rounded" required />
                </div>
                <div>
                    <label class="block text-sm font-medium">Xác nhận mật khẩu mới</label>
                    <input type="password" name="confirm_password" class="w-full border px-3 py-2 rounded" required />
                </div>
                <div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Đổi mật khẩu</button>
                </div>
            </form>
        </section>
    </main>

    <?php include BASE_PATH . 'components/footer.php'; ?>
</body>

</html>