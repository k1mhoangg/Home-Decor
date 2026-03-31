<?php
namespace Model;

use Core\Database;
use PDO;

class Cart
{
    public static function createOrderFromCart($cartId, $userId)
    {
        $db = Database::getInstance()->getConnection();
        // Lấy sản phẩm trong giỏ
        $items = self::getItems($cartId);
        if (empty($items)) return false;

        // Tính tổng tiền
        $total = 0;
        foreach ($items as $item) {
            $price = ($item['discount_price'] > 0) ? $item['discount_price'] : $item['price'];
            $total += $price * $item['quantity'];
        }

        // Tạo đơn hàng
        $stmt = $db->prepare("INSERT INTO orders (user_id, total_price, status, created_at) VALUES (:user_id, :total_price, 'chua_thanh_toan', NOW())");
        $stmt->execute([':user_id' => $userId, ':total_price' => $total]);
        $orderId = (int)$db->lastInsertId();

        // Thêm sản phẩm vào order_items
        $oi = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_each, total_price) VALUES (:order_id, :product_id, :quantity, :price_each, :total_price)");
        foreach ($items as $item) {
            $price = ($item['discount_price'] > 0) ? $item['discount_price'] : $item['price'];
            $oi->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':price_each' => $price,
                ':total_price' => $price * $item['quantity'],
            ]);
        }

        // Xóa giỏ hàng và cart_items
        $db->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id")->execute([':cart_id' => $cartId]);
        $db->prepare("DELETE FROM carts WHERE id = :id")->execute([':id' => $cartId]);

        return $orderId;
    }
        // Thêm sản phẩm vào giỏ hàng theo user id, tự tạo giỏ nếu chưa có
        public static function addToCartByUserId($userId, $productId, $quantity = 1)
        {
            $cart = self::getByUserId($userId);
            if (!$cart) {
                $cartId = self::create($userId);
            } else {
                $cartId = $cart['id'];
            }
            self::addItem($cartId, $productId, $quantity);
            return $cartId;
        }
    // Thêm sản phẩm vào giỏ hàng
    public static function addItem($cartId, $productId, $quantity = 1)
    {
        $db = Database::getInstance()->getConnection();
        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $stmt = $db->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1");
        $stmt->execute([':cart_id' => $cartId, ':product_id' => $productId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            // Nếu đã có thì cập nhật số lượng
            $newQty = $item['quantity'] + $quantity;
            $update = $db->prepare("UPDATE cart_items SET quantity = :quantity WHERE id = :id");
            $update->execute([':quantity' => $newQty, ':id' => $item['id']]);
        } else {
            // Nếu chưa có thì thêm mới
            $insert = $db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
            $insert->execute([':cart_id' => $cartId, ':product_id' => $productId, ':quantity' => $quantity]);
        }
    }    public static function getByUserId($userId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM carts WHERE user_id = :user_id LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cart ?: null;
    }

    // Tạo giỏ hàng mới cho user
    public static function create($userId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO carts (user_id, created_at) VALUES (:user_id, NOW())");
        $stmt->execute([':user_id' => $userId]);
        return (int)$db->lastInsertId();
    }

    // Lấy sản phẩm trong giỏ hàng
    public static function getItems($cartId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT ci.*, p.name, p.image_url, p.price, p.discount_price FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = :cart_id");
        $stmt->execute([':cart_id' => $cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
