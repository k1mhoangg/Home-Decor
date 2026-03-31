<?php

namespace Controller\Frontend;

use Model\User;

class UserController
{
    // Hiển thị trang chỉnh sửa profile
    public function edit()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $current = $_SESSION['user'] ?? null;
        if (!$current) {
            storePreviousUrl(); // Store current URL before redirecting to login
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập để truy cập trang này.'];
            header('Location: /login');
            exit;
        }

        $user = User::findById((int) $current['id']);
        require_once BASE_PATH . 'view/frontend/auth/profile.php';
    }

    // Xử lý POST cập nhật thông tin (display_name, email, avatar, username optional)
    public function update()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $current = $_SESSION['user'] ?? null;
        if (!$current) {
            storePreviousUrl(); // Store current URL before redirecting to login
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập.'];
            header('Location: /login');
            exit;
        }

        $id = (int) $current['id'];
        $display_name = sanitizeInput($_POST['display_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $username = sanitizeInput($_POST['username'] ?? '');

        $update = [];
        if ($display_name !== '')
            $update['display_name'] = $display_name;
        if ($email !== '')
            $update['email'] = $email;
        if ($username !== '')
            $update['username'] = $username;

        // Handle avatar upload
        if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['avatar'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Avatar chỉ nhận file ảnh (jpg, png, gif, webp).'];
                    header('Location: /profile');
                    exit;
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $id . '_' . time() . '.' . $ext;
                $uploadDir = BASE_PATH . 'public/uploads/avatars/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);
                $target = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $update['avatar'] = '/uploads/avatars/' . $filename; // public path
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Không thể lưu file avatar.'];
                    header('Location: /profile');
                    exit;
                }
            }
        }

        if (!empty($update)) {
            $ok = User::updateById($id, $update);
            if ($ok) {
                // Refresh session user data
                $user = User::findById($id);
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'role' => $user->getRole(),
                ];
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cập nhật thông tin thành công.'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Cập nhật thất bại.'];
            }
        } else {
            $_SESSION['flash'] = ['type' => 'info', 'message' => 'Không có thay đổi.'];
        }

        header('Location: /profile');
        exit;
    }

    // POST change password
    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $current = $_SESSION['user'] ?? null;
        if (!$current) {
            storePreviousUrl(); // Store current URL before redirecting to login
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập.'];
            header('Location: /login');
            exit;
        }

        $id = (int) $current['id'];
        $current_pass = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new_pass === '' || $current_pass === '') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin mật khẩu.'];
            header('Location: /profile');
            exit;
        }
        if ($new_pass !== $confirm) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Mật khẩu mới và xác nhận không khớp.'];
            header('Location: /profile');
            exit;
        }

        $user = User::findById($id);
        if (!$user || !$user->verifyPassword($current_pass)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Mật khẩu hiện tại không đúng.'];
            header('Location: /profile');
            exit;
        }

        $ok = User::updateById($id, ['password' => $new_pass]);
        if ($ok) {
            // Đổi mật khẩu thành công -> xóa session, yêu cầu đăng nhập lại
            unset($_SESSION['user']);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.'];
            header('Location: /login');
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Đổi mật khẩu thất bại.'];
            header('Location: /profile');
            exit;
        }
    }
}
