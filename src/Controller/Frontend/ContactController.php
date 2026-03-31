<?php

namespace Controller\Frontend;

use Model\Contact;
use Model\ContactSettings;

class ContactController
{
    public function index()
    {
        // Lấy settings từ database
        $settings = ContactSettings::getSettings();
        $activities = ContactSettings::parseListContent($settings['activities_content'] ?? '');
        $services = ContactSettings::parseListContent($settings['services_content'] ?? '');

        require_once BASE_PATH . 'view/frontend/contact.php';
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitizeInput($_POST['name'] ?? '');
            $email = sanitizeInput($_POST['email'] ?? '');
            $phone = sanitizeInput($_POST['phone'] ?? '');
            $subject = sanitizeInput($_POST['subject'] ?? '');
            $message = sanitizeInput($_POST['message'] ?? '');

            $contact = new Contact($name, $email, $message, $phone, $subject);
            if ($contact->save()) {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Gửi liên hệ thành công. Chúng tôi sẽ phản hồi sớm.'];
            } else {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gửi liên hệ thất bại, vui lòng thử lại.'];
            }

            header('Location: /contact');
            exit;
        }
    }
}