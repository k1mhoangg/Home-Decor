<?php
namespace Controller\Frontend;

class CartController
{
    public function checkout()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập để thanh toán.'];
            header('Location: /login');
            exit;
        }
        require_once BASE_PATH . '/Model/Cart.php';
        $cart = \Model\Cart::getByUserId($user['id']);
        if (!$cart) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Giỏ hàng không tồn tại.'];
            header('Location: /cart');
            exit;
        }
        $orderId = \Model\Cart::createOrderFromCart($cart['id'], $user['id']);
        if ($orderId) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thanh toán thành công! Đơn hàng #' . $orderId];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Thanh toán thất bại hoặc giỏ hàng trống.'];
        }
        header('Location: /cart');
        exit;
    }

        // Xử lý thêm sản phẩm vào giỏ hàng
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pricing');
            exit;
        }
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng.'];
            header('Location: /login');
            exit;
        }
        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
        if ($productId > 0) {
            require_once BASE_PATH . '/Model/Cart.php';
            \Model\Cart::addToCartByUserId($user['id'], $productId, $quantity);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm vào giỏ hàng!'];
        }
        // Chuyển về trang trước nếu có, nếu không thì về pricing
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer && (strpos($referer, '/pricing') === false)) {
            header('Location: ' . $referer);
        } else {
            header('Location: /pricing');
        }
        exit;
    }
    public function view()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vui lòng đăng nhập để xem giỏ hàng.'];
            header('Location: /login');
            exit;
        }
        require_once BASE_PATH . '/Model/Cart.php';
        $cart = \Model\Cart::getByUserId($user['id']);
        if (!$cart) {
            $cartId = \Model\Cart::create($user['id']);
            $cart = ['id' => $cartId, 'user_id' => $user['id']];
        }
        $items = \Model\Cart::getItems($cart['id']);
        require BASE_PATH . '/view/frontend/cart.php';
    }
}
