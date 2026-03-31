<?php
namespace Controller\Admin;

use Model\Product;
use Core\Session;
use Core\Pagination;

class ProductsController extends BaseAdminController
{
    public function index()
    {
        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $total = Product::count();
        $pagination = new Pagination($total, $perPage, $page);

        $products = Product::all($pagination->getLimit(), $pagination->getOffset());

        require_once BASE_PATH . 'view/admin/products.php';
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $product = $id ? Product::findById($id) : null;
        require_once BASE_PATH . 'view/admin/product_edit.php';
    }

    public function save()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'discount_price' => $_POST['discount_price'] !== '' ? (float) $_POST['discount_price'] : null,
            'category' => trim($_POST['category'] ?? ''),
            'stock' => (int) ($_POST['stock'] ?? 0),
            'is_featured' => !empty($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        // handle image upload
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($file['type'], $allowed)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $uploadDir = BASE_PATH . 'public/uploads/products/';
                    if (!is_dir($uploadDir))
                        mkdir($uploadDir, 0755, true);
                    $target = $uploadDir . $filename;
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $data['image_url'] = '/uploads/products/' . $filename;
                    }
                } else {
                    Session::setFlash('error', 'Ảnh sản phẩm không hợp lệ (jpg/png/gif/webp).');
                    header('Location: /admin/products');
                    exit;
                }
            }
        }

        if ($id) {
            $ok = Product::updateById($id, $data);
            Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật sản phẩm thành công.' : 'Cập nhật thất bại.');
        } else {
            $newId = Product::create($data);
            Session::setFlash($newId ? 'success' : 'error', $newId ? 'Tạo sản phẩm thành công.' : 'Tạo thất bại.');
        }

        header('Location: /admin/products');
        exit;
    }

    public function delete()
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id && Product::deleteById($id)) {
            Session::setFlash('success', 'Xóa sản phẩm thành công.');
        } else {
            Session::setFlash('error', 'Xóa sản phẩm thất bại.');
        }
        header('Location: /admin/products');
        exit;
    }
    
    public function search()
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $products = [];
        if ($keyword !== '') {
            $products = \Model\Product::searchByKeyword($keyword);
        }
        require_once BASE_PATH . 'view/admin/products.php';
    }
}
