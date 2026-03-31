<?php

namespace Model;

use \Core\Database;
use \PDO;

class ContactSettings
{
    public static function getSettings()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM contact_settings WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // Nếu chưa có record, tạo mặc định
            $db->exec("INSERT INTO contact_settings (id) VALUES (1)");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public static function updateSettings(array $data): bool
    {
        $db = Database::getInstance()->getConnection();
        $allowed = [
            'company_name',
            'company_short_name',
            'company_slogan',
            'intro_content',
            'activities_content',
            'services_content',
            'hotline',
            'hotline_2',
            'email',
            'address',
            'facebook_url',
            'google_url',
            'pinterest_url',
            'working_hours',
            'map_address',
            'map_embed'
        ];

        $sets = [];
        $params = [];

        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed))
                continue;
            $sets[] = "`$k` = :{$k}";
            $params[":{$k}"] = $v;
        }

        if (empty($sets))
            return false;

        $sql = "UPDATE contact_settings SET " . implode(', ', $sets) . " WHERE id = 1";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    // Helper để parse activities/services content thành array
    public static function parseListContent(?string $content): array
    {
        if (empty($content))
            return [];
        $lines = explode("\n", $content);
        return array_filter(array_map('trim', $lines));
    }

    // Helper để tạo Google Maps embed URL từ địa chỉ
    public static function generateMapEmbed(?string $address): string
    {
        if (empty($address))
            return '';
        $encodedAddress = urlencode($address);
        return '<iframe src="https://maps.google.com/maps?q=' . $encodedAddress . '&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
    }
}
