<?php

namespace Core;
class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0, // cookie sẽ tồn tại cho đến khi trình duyệt đóng
                'path' => '/',
                'domain' => '', // mặc định là domain hiện tại
                'secure' => isset($_SERVER['HTTPS']), // chỉ gửi cookie qua HTTPS nếu có
                'httponly' => true, // không cho phép truy cập cookie qua JavaScript
                'samesite' => 'Strict', // hoặc 'Strict' tùy nhu cầu
            ]);
            session_start();
        }
    }

    public static function setFlash($type, $message)
    {
        self::start();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash()
    {
        self::start();
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}