<?php

namespace Controller\Frontend;

use Model\Product;
use Model\HomeSettings;

class HomeController
{
    public function index()
    {
        $homeSettings = HomeSettings::getSettings();
        $slides = HomeSettings::getSlides(true);
        $banners = HomeSettings::getBanners(true);

        // Featured products: ưu tiên danh sách được admin chọn
        $featuredProducts = [];
        $featuredIds = HomeSettings::parseSelectedIds($homeSettings['featured_product_ids'] ?? '');
        if (!empty($featuredIds)) {
            $featuredProducts = Product::findByIds($featuredIds);
        } else {
            $featuredProducts = ($homeSettings['show_featured'] ?? 1) ? Product::getFeatured(6) : [];
        }

        // Recent products: ưu tiên danh sách được admin chọn
        $recentProducts = [];
        $recentIds = HomeSettings::parseSelectedIds($homeSettings['recent_product_ids'] ?? '');
        if (!empty($recentIds)) {
            $recentProducts = Product::findByIds($recentIds);
        } else {
            $recentProducts = ($homeSettings['show_recent'] ?? 1) ? Product::getRecent(6) : [];
        }

        require_once BASE_PATH . 'view/frontend/home.php';
    }
}