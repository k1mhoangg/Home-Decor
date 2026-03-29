<?php

namespace Controller\Frontend;

use Model\User;

class AuthController
{
    public function test()
    {
        echo "<div>AuthController from CONTROLLER.</div>";
    }
    public function index()
    {
        // ...existing behavior (auth-test)...
        echo "<div>Please give the class method a proper name.</div>";
    }

    // Hiển thị form đăng ký
    public function registerForm()
    {
        require_once BASE_PATH . 'view/frontend/auth/register.php';
    }

    // Xử lý POST đăng ký
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        // sanitize
        $username = sanitizeInput($_POST['username'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $displayName = sanitizeInput($_POST['display_name'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $password_confirm = trim($_POST['password_confirm'] ?? '');

        if (session_status() === PHP_SESSION_NONE)
            session_start();

        // Validation
        if ($username === '' || $password === '' || $email === '') {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng nhập đầy đủ thông tin bắt buộc (username, email, password).'];
            header('Location: /register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Email không hợp lệ.'];
            header('Location: /register');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.'];
            header('Location: /register');
            exit;
        }

        if ($password !== $password_confirm) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Xác nhận mật khẩu không khớp.'];
            header('Location: /register');
            exit;
        }

        try {
            // Create user with User::create() method
            $data = [
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'display_name' => $displayName ?: $username, // Use username if display_name is empty
                'role' => 'customer'
            ];

            $newId = User::create($data);

            if ($newId) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đăng ký thành công. Vui lòng đăng nhập.'];
                header('Location: /login');
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Đăng ký thất bại. Vui lòng thử lại.'];
                header('Location: /register');
            }
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Đăng ký thất bại: ' . $e->getMessage()];
            header('Location: /register');
            exit;
        }
    }

    // Hiển thị form đăng nhập
    public function loginForm()
    {
        require_once BASE_PATH . 'view/frontend/auth/login.php';
    }

    // Xử lý POST đăng nhập
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = sanitizeInput($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng nhập username và password.'];
            header('Location: /login');
            exit;
        }

        $user = User::findByUsername($username);
        if (!$user || !$user->verifyPassword($password)) {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.'];
            header('Location: /login');
            exit;
        }

        // set session user
        if (session_status() === PHP_SESSION_NONE)
            session_start();


        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'role' => $user->getRole()
        ];
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đăng nhập thành công.'];

        // Redirect to previous URL if it exists, otherwise redirect to home
        $previousUrl = getPreviousUrl();
        $redirectUrl = $previousUrl ? $previousUrl : '/';

        header('Location: ' . $redirectUrl);
        exit;
    }

    // Logout
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

        header('Location: /');
        exit;
    }
}