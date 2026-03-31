<?php
namespace Controller\Admin;

use Core\Session;

class BaseAdminController
{
    protected $currentUser;

    public function __construct()
    {
        Session::start();
        $u = $_SESSION['user'] ?? null;
        if (!$u || (($u['role'] ?? '') !== 'admin')) {
            Session::setFlash('error', 'Vui lòng đăng nhập với tài khoản quản trị để truy cập trang này.');
            header('Location: /admin/login');
            exit;
        }
        $this->currentUser = $u;
    }
}
