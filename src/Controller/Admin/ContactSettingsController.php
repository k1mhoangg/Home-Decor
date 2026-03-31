<?php

namespace Controller\Admin;

use Model\ContactSettings;
use Core\Session;

class ContactSettingsController extends BaseAdminController
{
    public function index()
    {
        $settings = ContactSettings::getSettings();
        require_once BASE_PATH . 'view/admin/contact_settings.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/contact-settings');
            exit;
        }

        $data = [];

        // Company info
        if (isset($_POST['company_name'])) {
            $data['company_name'] = trim($_POST['company_name']);
        }
        if (isset($_POST['company_short_name'])) {
            $data['company_short_name'] = trim($_POST['company_short_name']);
        }
        if (isset($_POST['company_slogan'])) {
            $data['company_slogan'] = trim($_POST['company_slogan']);
        }

        // Content
        if (isset($_POST['intro_content'])) {
            $data['intro_content'] = trim($_POST['intro_content']);
        }
        if (isset($_POST['activities_content'])) {
            $data['activities_content'] = trim($_POST['activities_content']);
        }
        if (isset($_POST['services_content'])) {
            $data['services_content'] = trim($_POST['services_content']);
        }

        // Contact info
        if (isset($_POST['hotline'])) {
            $data['hotline'] = trim($_POST['hotline']);
        }
        if (isset($_POST['hotline_2'])) {
            $data['hotline_2'] = trim($_POST['hotline_2']);
        }
        if (isset($_POST['email'])) {
            $data['email'] = trim($_POST['email']);
        }
        if (isset($_POST['address'])) {
            $data['address'] = trim($_POST['address']);
        }

        // Social links
        if (isset($_POST['facebook_url'])) {
            $data['facebook_url'] = trim($_POST['facebook_url']);
        }
        if (isset($_POST['google_url'])) {
            $data['google_url'] = trim($_POST['google_url']);
        }
        if (isset($_POST['pinterest_url'])) {
            $data['pinterest_url'] = trim($_POST['pinterest_url']);
        }

        // Other
        if (isset($_POST['working_hours'])) {
            $data['working_hours'] = trim($_POST['working_hours']);
        }

        // Map address - tự động tạo embed từ địa chỉ
        if (isset($_POST['map_address'])) {
            $mapAddress = trim($_POST['map_address']);
            $data['map_address'] = $mapAddress;
            // Tự động tạo map_embed từ địa chỉ
            $data['map_embed'] = ContactSettings::generateMapEmbed($mapAddress);
        }

        $ok = ContactSettings::updateSettings($data);
        Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật cài đặt trang liên hệ thành công.' : 'Cập nhật thất bại.');

        header('Location: /admin/contact-settings');
        exit;
    }
}
