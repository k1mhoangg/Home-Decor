<?php
namespace Controller\Admin;

use Model\User;
use Model\Contact;
use Core\Pagination;
use Core\Session;

class AdminController extends BaseAdminController
{
    public function index()
    {
        // BaseAdminController::__construct đã đảm bảo session và quyền admin
        $users = User::all();              // trả về mảng User objects
        $contacts = Contact::getAll();     // trả về mảng assoc

        $countUsers = User::count();
        $countContacts = Contact::count();

        // Lấy users mới nhất (có created_at)
        $recentUsers = User::recent(5);

        // Lấy contacts mới nhất
        $recentContacts = Contact::recent(5);

        // Biến truyền vào view: $countUsers, $countContacts, $recentUsers, $recentContacts
        require_once BASE_PATH . 'view/admin/dashboard.php';
    }

    public function listUsers()
    {
        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $total = User::count();
        $pagination = new Pagination($total, $perPage, $page);

        $users = User::all($pagination->getLimit(), $pagination->getOffset());

        require_once BASE_PATH . 'view/admin/users.php';
    }

    public function listContacts()
    {
        // Logic to get contacts from the database
        $contacts = Contact::getAll(); // Giả sử lấy từ model Contact

        require_once BASE_PATH . 'view/admin/contacts.php';
    }

    public function createUser()
    {
        $user = null;
        $isEdit = false;
        require_once BASE_PATH . 'view/admin/user_edit.php';
    }

    public function editUser()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $user = User::findById($id);
        if (!$user) {
            Session::setFlash('error', 'Người dùng không tồn tại.');
            header('Location: /admin/users');
            exit;
        }
        $isEdit = true;
        require_once BASE_PATH . 'view/admin/user_edit.php';
    }

    public function saveUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $displayName = trim($_POST['display_name'] ?? '');
        $role = trim($_POST['role'] ?? 'customer');
        $password = trim($_POST['password'] ?? '');

        // Validation
        if (empty($username)) {
            Session::setFlash('error', 'Username không được để trống.');
            header('Location: /admin/users/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        if (empty($email)) {
            Session::setFlash('error', 'Email không được để trống.');
            header('Location: /admin/users/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        // Check username duplicate (except current user)
        $existingUser = User::findByUsername($username);
        if ($existingUser && (!$id || $existingUser->getId() !== $id)) {
            Session::setFlash('error', 'Username đã tồn tại.');
            header('Location: /admin/users/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'display_name' => $displayName,
            'role' => $role
        ];

        // Handle avatar upload
        if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['avatar'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    Session::setFlash('error', 'Avatar chỉ nhận file ảnh (jpg, png, gif, webp).');
                    header('Location: /admin/users/' . ($id ? 'edit?id=' . $id : 'create'));
                    exit;
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $uploadDir = BASE_PATH . 'public/uploads/avatars/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);
                $target = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $data['avatar'] = '/uploads/avatars/' . $filename;
                } else {
                    Session::setFlash('error', 'Không thể lưu file avatar.');
                    header('Location: /admin/users/' . ($id ? 'edit?id=' . $id : 'create'));
                    exit;
                }
            }
        }

        try {
            if ($id) {
                // Update existing user
                if (!empty($password)) {
                    $data['password'] = $password; // Model will hash it
                }
                $ok = User::updateById($id, $data);
                Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật người dùng thành công.' : 'Cập nhật thất bại.');
            } else {
                // Create new user
                if (empty($password)) {
                    Session::setFlash('error', 'Mật khẩu không được để trống khi tạo người dùng mới.');
                    header('Location: /admin/users/create');
                    exit;
                }
                $data['password'] = $password;
                $newId = User::create($data);
                Session::setFlash($newId ? 'success' : 'error', $newId ? 'Tạo người dùng thành công.' : 'Tạo thất bại.');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', 'Lỗi: ' . $e->getMessage());
            header('Location: /admin/users/' . ($id ? 'edit?id=' . $id : 'create'));
            exit;
        }

        header('Location: /admin/users');
        exit;
    }

    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        // Validation
        if (!$id) {
            Session::setFlash('error', 'ID người dùng không hợp lệ.');
            header('Location: /admin/users');
            exit;
        }

        // Prevent deleting self
        if ($id === (int) $this->currentUser['id']) {
            Session::setFlash('error', 'Không thể xóa chính bạn.');
            header('Location: /admin/users');
            exit;
        }

        // Check if user exists
        $user = User::findById($id);
        if (!$user) {
            Session::setFlash('error', 'Người dùng không tồn tại.');
            header('Location: /admin/users');
            exit;
        }

        try {
            if (User::deleteById($id)) {
                Session::setFlash('success', 'Xóa người dùng thành công.');
            } else {
                Session::setFlash('error', 'Xóa người dùng thất bại.');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', 'Lỗi: ' . $e->getMessage());
        }

        header('Location: /admin/users');
        exit;
    }
}