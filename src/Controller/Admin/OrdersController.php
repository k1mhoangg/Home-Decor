<?php
namespace Controller\Admin;

use Core\Database;

class OrdersController extends BaseAdminController
{
    public function listOrders()
    {
        require_once BASE_PATH . '/Model/Order.php';
        $perPage = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $total = \Model\Order::count();
        $pagination = new \Core\Pagination($total, $perPage, $page);
        $orders = \Model\Order::all($pagination->getLimit(), $pagination->getOffset());

        // Flash message
        if (class_exists('\Core\Session')) {
            $flash = \Core\Session::getFlash();
        } else {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            $flash = $_SESSION['flash'] ?? null;
            if ($flash)
                unset($_SESSION['flash']);
        }

        require BASE_PATH . 'view/admin/orders.php';
    }

    public function viewOrder()
    {
        require_once BASE_PATH . '/Model/Order.php';
        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $order = $orderId ? \Model\Order::findById($orderId) : null;
        $items = $order ? \Model\Order::getItems($orderId) : [];

        // Flash message
        if (class_exists('\Core\Session')) {
            $flash = \Core\Session::getFlash();
        } else {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            $flash = $_SESSION['flash'] ?? null;
            if ($flash)
                unset($_SESSION['flash']);
        }

        require BASE_PATH . 'view/admin/order_detail.php';
    }

    public function confirmOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method Not Allowed';
            return;
        }
        $orderId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($orderId <= 0) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID đơn hàng không hợp lệ!'];
            header('Location: /admin/orders');
            exit;
        }
        require_once BASE_PATH . '/Model/Order.php';
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute([':status' => 'da_thanh_toan', ':id' => $orderId]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xác nhận thanh toán cho đơn hàng #' . $orderId];
        header('Location: /admin/orders/view?id=' . $orderId);
        exit;
    }

    public function deleteOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method Not Allowed';
            return;
        }
        $orderId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($orderId <= 0) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID đơn hàng không hợp lệ!'];
            header('Location: /admin/orders');
            exit;
        }
        require_once BASE_PATH . '/Model/Order.php';
        $db = Database::getInstance()->getConnection();
        // Xóa order_items trước
        $db->prepare('DELETE FROM order_items WHERE order_id = :order_id')->execute([':order_id' => $orderId]);
        // Xóa order
        $db->prepare('DELETE FROM orders WHERE id = :id')->execute([':id' => $orderId]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa đơn hàng #' . $orderId];
        header('Location: /admin/orders');
        exit;
    }
}
