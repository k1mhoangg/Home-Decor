<?php
namespace Controller\Admin;

use Model\User;
use Core\Session;

class AuthController
{
    // Hiển thị form login cho admin
    public function loginForm()
    {
        // nếu đã login với role admin, chuyển hướng về dashboard
        Session::start();
        $u = $_SESSION['user'] ?? null;
        if ($u && ($u['role'] ?? '') === 'admin') {
            header('Location: /admin');
            exit;
        }

        require_once BASE_PATH . 'view/admin/login.php';
    }

    // Xử lý POST đăng nhập admin
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/login');
            exit;
        }

        Session::start();

        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($username === '' || $password === '') {
            Session::setFlash('error', 'Vui lòng nhập username và password.');
            header('Location: /admin/login');
            exit;
        }

        $user = User::findByUsername($username);
        if (!$user || !$user->verifyPassword($password) || ($user->getRole() !== 'admin')) {
            Session::setFlash('error', 'Tên đăng nhập hoặc mật khẩu không đúng / không có quyền admin.');
            header('Location: /admin/login');
            exit;
        }

        session_regenerate_id(true);

        // set session user (admin)
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'role' => $user->getRole()
        ];
        Session::setFlash('success', 'Đăng nhập quản trị thành công.');

        header('Location: /admin');
        exit;
    }

    // Logout admin
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        session_destroy();

        setcookie(
            session_name(),
            '',
            time() - 3600,
            '/',
            '',
            isset($_SERVER['HTTPS']),
            true
        );

        setcookie(
            'flash',
            json_encode([
                'type' => 'success',
                'message' => 'Bạn đã đăng xuất.'
            ]),
            time() + 5,
            '/'
        );

        header('Location: /admin/login');
        exit;
    }
}
