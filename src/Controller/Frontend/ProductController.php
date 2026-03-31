<?php
namespace Controller\Frontend;

class ProductController

{
    public function detail($id)
    {
        require_once BASE_PATH . '/Model/Product.php';
        $product = \Model\Product::findById((int)$id);
        if (!$product) {
            http_response_code(404);
            require BASE_PATH . '/view/frontend/404.php';
            return;
        }
        require BASE_PATH . '/view/frontend/product_detail.php';
    }
    // Tìm kiếm sản phẩm theo từ khóa
    public function search()
    {
        require_once BASE_PATH . '/Model/Product.php';
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $products = [];
        if ($keyword !== '') {
            $products = \Model\Product::searchByKeyword($keyword);
        }
        // Nếu không có từ khóa, trả về tất cả sản phẩm
        if ($keyword === '') {
            $products = \Model\Product::all();
        }
        // Phân trang (optional, nếu cần)
        require BASE_PATH . '/view/frontend/pricing.php';
    }
}
