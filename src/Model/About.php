<?php

namespace Model;

use \Core\Database;
use \PDO;
use \Exception;

class About
{
    // Get about settings (chỉ có 1 record với id=1)
    public static function getSettings()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM about_settings WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            // Nếu chưa có record, tạo mặc định
            $db->exec("INSERT INTO about_settings (id) VALUES (1)");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    // Update about settings
    public static function updateSettings(array $data): bool
    {
        $db = Database::getInstance()->getConnection();
        $allowed = ['banner_image', 'banner_image_thumb', 'intro_content', 'vision_image', 'vision_image_thumb', 'vision_content', 
                   'mission_content', 'values_content', 'decor_content',
                   'customers_target', 'years_target', 'projects_target', 'loyal_customers_target'];
        
        $sets = [];
        $params = [];
        
        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed)) continue;
            $sets[] = "`$k` = :{$k}";
            $params[":{$k}"] = $v;
        }
        
        if (empty($sets)) return false;
        
        $sql = "UPDATE about_settings SET " . implode(', ', $sets) . " WHERE id = 1";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    // Get all decor images ordered by display_order
    public static function getDecorImages(): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM about_decor_images ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get decor image by id
    public static function getDecorImageById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM about_decor_images WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Create decor image
    public static function createDecorImage(string $imageUrl, int $displayOrder = 0): int
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO about_decor_images (image_url, display_order) VALUES (:image_url, :display_order)");
        $stmt->execute([
            ':image_url' => $imageUrl,
            ':display_order' => $displayOrder
        ]);
        return (int) $db->lastInsertId();
    }

    // Update decor image
    public static function updateDecorImage(int $id, array $data): bool
    {
        $db = Database::getInstance()->getConnection();
        $allowed = ['image_url', 'display_order'];
        $sets = [];
        $params = [':id' => $id];
        
        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed)) continue;
            $sets[] = "`$k` = :{$k}";
            $params[":{$k}"] = $v;
        }
        
        if (empty($sets)) return false;
        
        $sql = "UPDATE about_decor_images SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete decor image
    public static function deleteDecorImage(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM about_decor_images WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Get all advantages ordered by display_order
    public static function getAdvantages(): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM about_advantages ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get advantage by id
    public static function getAdvantageById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM about_advantages WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Create advantage
    public static function createAdvantage(string $content, int $displayOrder = 0): int
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO about_advantages (content, display_order) VALUES (:content, :display_order)");
        $stmt->execute([
            ':content' => $content,
            ':display_order' => $displayOrder
        ]);
        return (int) $db->lastInsertId();
    }

    // Update advantage
    public static function updateAdvantage(int $id, array $data): bool
    {
        $db = Database::getInstance()->getConnection();
        $allowed = ['content', 'display_order'];
        $sets = [];
        $params = [':id' => $id];
        
        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed)) continue;
            $sets[] = "`$k` = :{$k}";
            $params[":{$k}"] = $v;
        }
        
        if (empty($sets)) return false;
        
        $sql = "UPDATE about_advantages SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete advantage
    public static function deleteAdvantage(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM about_advantages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

