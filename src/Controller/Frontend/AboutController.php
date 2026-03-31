<?php

namespace Controller\Frontend;

use Model\About;

class AboutController
{
    public function index()
    {
        $settings = About::getSettings();
        $decorImages = About::getDecorImages();
        $advantages = About::getAdvantages();
        
        require_once BASE_PATH . 'view/frontend/about.php';
    }
}