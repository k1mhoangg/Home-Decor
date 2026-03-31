<?php
namespace Model;

use \Core\Database;
use \PDO;
use \Exception;

class Product
{
    public static function all(int $limit = 0, int $offset = 0): array
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM products ORDER BY created_at DESC";
        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $db->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count(): int
    {
        $db = Database::getInstance()->getConnection();
        return (int) $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public static function findById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO products (name, description, price, discount_price, category, stock, image_url, is_featured, is_active) VALUES (:name,:description,:price,:discount_price,:category,:stock,:image_url,:is_featured,:is_active)");
        $stmt->execute([
            ':name' => $data['name'] ?? '',
            ':description' => $data['description'] ?? '',
            ':price' => $data['price'] ?? 0,
            ':discount_price' => $data['discount_price'] ?? null,
            ':category' => $data['category'] ?? null,
            ':stock' => $data['stock'] ?? 0,
            ':image_url' => $data['image_url'] ?? null,
            ':is_featured' => !empty($data['is_featured']) ? 1 : 0,
            ':is_active' => isset($data['is_active']) ? (int) $data['is_active'] : 1,
        ]);
        return (int) $db->lastInsertId();
    }

    public static function updateById(int $id, array $data): bool
    {
        $allowed = ['name', 'description', 'price', 'discount_price', 'category', 'stock', 'image_url', 'is_featured', 'is_active'];
        $sets = [];
        $params = [':id' => $id];
        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed))
                continue;
            $sets[] = "`$k` = :$k";
            if ($k === 'is_featured' || $k === 'is_active')
                $v = $v ? 1 : 0;
            $params[":$k"] = $v;
        }
        if (empty($sets))
            return false;
        $sql = "UPDATE products SET " . implode(', ', $sets) . " WHERE id = :id";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function deleteById(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function getFeatured(int $limit = 6): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE is_featured = 1 AND is_active = 1 ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRecent(int $limit = 6): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function searchByKeyword(string $keyword): array
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM products WHERE name LIKE :kw ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $kw = '%' . $keyword . '%';
        $stmt->bindValue(':kw', $kw, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy nhiều product theo mảng ID, giữ đúng thứ tự input
     */
    public static function findByIds(array $ids): array
    {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (empty($ids))
            return [];

        $db = Database::getInstance()->getConnection();
        $in = implode(',', $ids);
        $sql = "SELECT * FROM products WHERE id IN ($in) ORDER BY FIELD(id, $in)";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
