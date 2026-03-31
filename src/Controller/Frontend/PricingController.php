<?php
namespace Controller\Frontend;

class PricingController
{
    public function index()
    {
        // Lấy dữ liệu sản phẩm từ database
        require_once BASE_PATH . '/Model/Product.php';
        require_once BASE_PATH . '/Core/Pagination.php';

        $perPage = 12;
        $total = \Model\Product::count();
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $pagination = new \Core\Pagination($total, $perPage, $currentPage);
        $products = \Model\Product::all($pagination->getLimit(), $pagination->getOffset());

        require BASE_PATH . '/view/frontend/pricing.php';
    }
}
