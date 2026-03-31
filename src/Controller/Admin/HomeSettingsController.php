<?php
namespace Controller\Admin;

use Model\HomeSettings;
use Model\Product;
use Core\Session;

require_once BASE_PATH . 'Core/utils.php';

class HomeSettingsController extends BaseAdminController
{
    public function index()
    {
        $settings = HomeSettings::getSettings();
        $slides = HomeSettings::getSlides(false);
        $banners = HomeSettings::getBanners(false);
        $allProducts = Product::all(0, 0);

        $activeTab = isset($_GET['tab']) ? sanitizeInput($_GET['tab']) : 'settings';

        require_once BASE_PATH . 'view/admin/home_settings.php';
    }

    public function saveSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/home-settings');
            exit;
        }

        $data = [];

        // Hero section
        if (isset($_POST['hero_title']))
            $data['hero_title'] = trim($_POST['hero_title']);
        if (isset($_POST['hero_subtitle']))
            $data['hero_subtitle'] = trim($_POST['hero_subtitle']);
        if (isset($_POST['hero_button_text']))
            $data['hero_button_text'] = trim($_POST['hero_button_text']);
        if (isset($_POST['hero_button_link']))
            $data['hero_button_link'] = trim($_POST['hero_button_link']);

        // Featured section
        if (isset($_POST['featured_title']))
            $data['featured_title'] = trim($_POST['featured_title']);
        if (isset($_POST['featured_subtitle']))
            $data['featured_subtitle'] = trim($_POST['featured_subtitle']);
        $data['show_featured'] = isset($_POST['show_featured']) ? 1 : 0;

        // Recent section
        if (isset($_POST['recent_title']))
            $data['recent_title'] = trim($_POST['recent_title']);
        if (isset($_POST['recent_subtitle']))
            $data['recent_subtitle'] = trim($_POST['recent_subtitle']);
        $data['show_recent'] = isset($_POST['show_recent']) ? 1 : 0;

        // Carousel section
        $data['show_carousel'] = isset($_POST['show_carousel']) ? 1 : 0;

        // Banner section
        $data['show_banner'] = isset($_POST['show_banner']) ? 1 : 0;

        // Categories section
        $data['show_categories'] = isset($_POST['show_categories']) ? 1 : 0;
        if (isset($_POST['categories_title']))
            $data['categories_title'] = trim($_POST['categories_title']);

        // Selected product IDs
        if (!empty($_POST['featured_product_ids']) && is_array($_POST['featured_product_ids'])) {
            $ids = array_map('intval', $_POST['featured_product_ids']);
            $data['featured_product_ids'] = implode(',', array_filter($ids));
        } else {
            $data['featured_product_ids'] = '';
        }

        if (!empty($_POST['recent_product_ids']) && is_array($_POST['recent_product_ids'])) {
            $ids = array_map('intval', $_POST['recent_product_ids']);
            $data['recent_product_ids'] = implode(',', array_filter($ids));
        } else {
            $data['recent_product_ids'] = '';
        }

        $ok = HomeSettings::updateSettings($data);
        Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật cài đặt thành công.' : 'Cập nhật thất bại.');

        header('Location: /admin/home-settings?tab=settings');
        exit;
    }

    // Slides CRUD
    public function createSlide()
    {
        $slide = null;
        $isEdit = false;
        $allProducts = Product::all(0, 0);
        require_once BASE_PATH . 'view/admin/home_slide_edit.php';
    }

    public function editSlide()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $slide = HomeSettings::getSlideById($id);
        if (!$slide) {
            Session::setFlash('error', 'Slide không tồn tại.');
            header('Location: /admin/home-settings?tab=slides');
            exit;
        }
        $isEdit = true;
        $allProducts = Product::all(0, 0);
        require_once BASE_PATH . 'view/admin/home_slide_edit.php';
    }

    public function saveSlide()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

        if (!$productId) {
            Session::setFlash('error', 'Vui lòng chọn sản phẩm để lấy ảnh.');
            header('Location: /admin/home-settings/slides/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        // Lấy ảnh từ sản phẩm
        $product = Product::findById($productId);
        if (!$product) {
            Session::setFlash('error', 'Sản phẩm không tồn tại.');
            header('Location: /admin/home-settings/slides/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        $data = [
            'product_id' => $productId,
            'image_url' => $product['image_url'],
            'title' => trim($_POST['title'] ?? ''),
            'subtitle' => trim($_POST['subtitle'] ?? ''),
            'button_text' => trim($_POST['button_text'] ?? ''),
            'button_link' => trim($_POST['button_link'] ?? ''),
            'display_order' => (int) ($_POST['display_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if ($id) {
            $ok = HomeSettings::updateSlide($id, $data);
            Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật slide thành công.' : 'Cập nhật thất bại.');
        } else {
            $newId = HomeSettings::createSlide($data);
            Session::setFlash($newId ? 'success' : 'error', $newId ? 'Thêm slide thành công.' : 'Thêm thất bại.');
        }

        header('Location: /admin/home-settings?tab=slides');
        exit;
    }

    public function deleteSlide()
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id && HomeSettings::deleteSlide($id)) {
            Session::setFlash('success', 'Xóa slide thành công.');
        } else {
            Session::setFlash('error', 'Xóa thất bại.');
        }
        header('Location: /admin/home-settings?tab=slides');
        exit;
    }

    // Banners CRUD (similar to slides)
    public function createBanner()
    {
        $banner = null;
        $isEdit = false;
        $allProducts = Product::all(0, 0);
        require_once BASE_PATH . 'view/admin/home_banner_edit.php';
    }

    public function editBanner()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $banner = HomeSettings::getBannerById($id);
        if (!$banner) {
            Session::setFlash('error', 'Banner không tồn tại.');
            header('Location: /admin/home-settings?tab=banners');
            exit;
        }
        $isEdit = true;
        $allProducts = Product::all(0, 0);
        require_once BASE_PATH . 'view/admin/home_banner_edit.php';
    }

    public function saveBanner()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

        if (!$productId) {
            Session::setFlash('error', 'Vui lòng chọn sản phẩm để lấy ảnh.');
            header('Location: /admin/home-settings/banners/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        // Lấy ảnh từ sản phẩm
        $product = Product::findById($productId);
        if (!$product) {
            Session::setFlash('error', 'Sản phẩm không tồn tại.');
            header('Location: /admin/home-settings/banners/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        $data = [
            'product_id' => $productId,
            'image_url' => $product['image_url'],
            'title' => trim($_POST['title'] ?? ''),
            'link' => trim($_POST['link'] ?? '/'),
            'display_order' => (int) ($_POST['display_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if ($id) {
            $ok = HomeSettings::updateBanner($id, $data);
            Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật banner thành công.' : 'Cập nhật thất bại.');
        } else {
            $newId = HomeSettings::createBanner($data);
            Session::setFlash($newId ? 'success' : 'error', $newId ? 'Thêm banner thành công.' : 'Thêm thất bại.');
        }

        header('Location: /admin/home-settings?tab=banners');
        exit;
    }

    public function deleteBanner()
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id && HomeSettings::deleteBanner($id)) {
            Session::setFlash('success', 'Xóa banner thành công.');
        } else {
            Session::setFlash('error', 'Xóa thất bại.');
        }
        header('Location: /admin/home-settings?tab=banners');
        exit;
    }
}
