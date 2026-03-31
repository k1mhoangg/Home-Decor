<?php
namespace Model;

use \Core\Database;
use \PDO;

class HomeSettings
{
    public static function getSettings()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM home_settings WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $db->exec("INSERT INTO home_settings (id) VALUES (1)");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $row ?: [];
    }

    public static function updateSettings(array $data): bool
    {
        $db = Database::getInstance()->getConnection();
        $allowed = [
            'hero_title',
            'hero_subtitle',
            'hero_button_text',
            'hero_button_link',
            'show_carousel',
            'featured_title',
            'featured_subtitle',
            'show_featured',
            'featured_product_ids',
            'recent_title',
            'recent_subtitle',
            'show_recent',
            'recent_product_ids',
            'show_banner',
            'show_categories',
            'categories_title'
        ];

        $sets = [];
        $params = [];
        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed))
                continue;
            $sets[] = "`$k` = :$k";
            $params[":$k"] = $v;
        }

        if (empty($sets))
            return false;

        $sql = "UPDATE home_settings SET " . implode(', ', $sets) . " WHERE id = 1";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    // Slides management
    public static function getSlides(bool $activeOnly = true): array
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM home_slides";
        if ($activeOnly)
            $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY display_order ASC, id ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSlideById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM home_slides WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function createSlide(array $data): int
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO home_slides (product_id, image_url, title, subtitle, button_text, button_link, display_order, is_active) 
                              VALUES (:product_id, :image_url, :title, :subtitle, :button_text, :button_link, :display_order, :is_active)");
        $stmt->execute([
            ':product_id' => $data['product_id'] ?? null,
            ':image_url' => $data['image_url'],
            ':title' => $data['title'] ?? '',
            ':subtitle' => $data['subtitle'] ?? '',
            ':button_text' => $data['button_text'] ?? '',
            ':button_link' => $data['button_link'] ?? '',
            ':display_order' => $data['display_order'] ?? 0,
            ':is_active' => $data['is_active'] ?? 1
        ]);
        return (int) $db->lastInsertId();
    }

    public static function updateSlide(int $id, array $data): bool
    {
        $db = Database::getInstance()->getConnection();
        $allowed = ['product_id', 'image_url', 'title', 'subtitle', 'button_text', 'button_link', 'display_order', 'is_active'];
        $sets = [];
        $params = [':id' => $id];

        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed))
                continue;
            $sets[] = "`$k` = :{$k}";
            $params[":{$k}"] = $v;
        }

        if (empty($sets))
            return false;

        $sql = "UPDATE home_slides SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function deleteSlide(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM home_slides WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Banners management
    public static function getBanners(bool $activeOnly = true): array
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM home_banners";
        if ($activeOnly)
            $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY display_order ASC, id ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getBannerById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM home_banners WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function createBanner(array $data): int
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO home_banners (product_id, image_url, title, link, display_order, is_active) 
                              VALUES (:product_id, :image_url, :title, :link, :display_order, :is_active)");
        $stmt->execute([
            ':product_id' => $data['product_id'] ?? null,
            ':image_url' => $data['image_url'],
            ':title' => $data['title'] ?? '',
            ':link' => $data['link'] ?? '/',
            ':display_order' => $data['display_order'] ?? 0,
            ':is_active' => $data['is_active'] ?? 1
        ]);
        return (int) $db->lastInsertId();
    }

    public static function updateBanner(int $id, array $data): bool
    {
        $db = Database::getInstance()->getConnection();
        $allowed = ['product_id', 'image_url', 'title', 'link', 'display_order', 'is_active'];
        $sets = [];
        $params = [':id' => $id];

        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed))
                continue;
            $sets[] = "`$k` = :{$k}";
            $params[":{$k}"] = $v;
        }

        if (empty($sets))
            return false;

        $sql = "UPDATE home_banners SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function deleteBanner(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM home_banners WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Parse CSV string "1,2,3" => [1,2,3]
     */
    public static function parseSelectedIds(?string $csv): array
    {
        if (empty($csv))
            return [];
        $parts = array_filter(array_map('trim', explode(',', $csv)));
        return array_values(array_map('intval', $parts));
    }
}
