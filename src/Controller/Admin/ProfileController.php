<?php
namespace Controller\Admin;

use Model\User;
use Core\Session;

class ProfileController extends BaseAdminController
{
    public function edit()
    {
        $user = User::findById((int) $this->currentUser['id']);
        require_once BASE_PATH . 'view/admin/profile.php';
    }

    public function update()
    {
        $id = (int) $this->currentUser['id'];
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
                    Session::setFlash('error', 'Avatar chỉ nhận file ảnh (jpg, png, gif, webp).');
                    header('Location: /admin/profile');
                    exit;
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_admin_' . $id . '_' . time() . '.' . $ext;
                $uploadDir = BASE_PATH . 'public/uploads/avatars/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);
                $target = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $update['avatar'] = '/uploads/avatars/' . $filename;
                } else {
                    Session::setFlash('error', 'Không thể lưu file avatar.');
                    header('Location: /admin/profile');
                    exit;
                }
            }
        }

        if (!empty($update)) {
            $ok = User::updateById($id, $update);
            if ($ok) {
                $user = User::findById($id);
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'role' => $user->getRole(),
                ];
                Session::setFlash('success', 'Cập nhật thông tin thành công.');
            } else {
                Session::setFlash('error', 'Cập nhật thất bại.');
            }
        } else {
            Session::setFlash('info', 'Không có thay đổi.');
        }

        header('Location: /admin/profile');
        exit;
    }

    public function changePassword()
    {
        $id = (int) $this->currentUser['id'];
        $current_pass = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($new_pass === '' || $current_pass === '') {
            Session::setFlash('error', 'Vui lòng điền đầy đủ thông tin mật khẩu.');
            header('Location: /admin/profile');
            exit;
        }
        if ($new_pass !== $confirm) {
            Session::setFlash('error', 'Mật khẩu mới và xác nhận không khớp.');
            header('Location: /admin/profile');
            exit;
        }

        $user = User::findById($id);
        if (!$user || !$user->verifyPassword($current_pass)) {
            Session::setFlash('error', 'Mật khẩu hiện tại không đúng.');
            header('Location: /admin/profile');
            exit;
        }

        $ok = User::updateById($id, ['password' => $new_pass]);
        if ($ok) {
            // Đổi mật khẩu thành công -> xóa session, yêu cầu đăng nhập lại
            unset($_SESSION['user']);
            Session::setFlash('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.');
            header('Location: /admin/login');
            exit;
        } else {
            Session::setFlash('error', 'Đổi mật khẩu thất bại.');
            header('Location: /admin/profile');
            exit;
        }
    }
}
