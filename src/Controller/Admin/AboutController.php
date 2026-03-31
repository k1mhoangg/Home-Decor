<?php
namespace Controller\Admin;

use Model\About;
use Core\Session;

require_once BASE_PATH . 'Core/utils.php';

class AboutController extends BaseAdminController
{
    public function index()
    {
        $settings = About::getSettings();
        $decorImages = About::getDecorImages();
        $advantages = About::getAdvantages();
        require_once BASE_PATH . 'view/admin/about.php';
    }

    public function createDecor()
    {
        $decorImage = null;
        $isEdit = false;
        require_once BASE_PATH . 'view/admin/about_decor_edit.php';
    }

    public function editDecor()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $decorImage = $id ? About::getDecorImageById($id) : null;
        if (!$decorImage) {
            Session::setFlash('error', 'Ảnh nội thất không tồn tại.');
            header('Location: /admin/about?tab=decor-images');
            exit;
        }
        $isEdit = true;
        require_once BASE_PATH . 'view/admin/about_decor_edit.php';
    }

    public function createAdvantage()
    {
        $advantage = null;
        $isEdit = false;
        require_once BASE_PATH . 'view/admin/about_advantage_edit.php';
    }

    public function editAdvantage()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $advantage = null;
        $list = About::getAdvantages();
        foreach ($list as $item) {
            if ((int) $item['id'] === $id) {
                $advantage = $item;
                break;
            }
        }
        if (!$advantage) {
            Session::setFlash('error', 'Ưu thế không tồn tại.');
            header('Location: /admin/about?tab=advantages');
            exit;
        }
        $isEdit = true;
        require_once BASE_PATH . 'view/admin/about_advantage_edit.php';
    }

    private function validateImageSize($filePath, $minWidth = 1200, $minHeight = 600)
    {
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo === false) {
            return ['valid' => false, 'message' => 'Không thể đọc thông tin ảnh.'];
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        if ($width < $minWidth || $height < $minHeight) {
            return [
                'valid' => false, 
                'message' => "Ảnh phải có kích thước tối thiểu {$minWidth}x{$minHeight}px. Ảnh hiện tại: {$width}x{$height}px."
            ];
        }
        
        return ['valid' => true, 'width' => $width, 'height' => $height];
    }

    private function processImageUpload($tempPath, $baseFilename, $uploadDir, $maxWidth, $maxHeight, $quality, $createThumbnail = false)
    {
        // Validate image size
        $validation = $this->validateImageSize($tempPath);
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }

        // Save original (for frontend) - giữ nguyên kích thước gốc
        $originalPath = $uploadDir . $baseFilename;
        if (!copy($tempPath, $originalPath)) {
            return ['success' => false, 'message' => 'Không thể lưu ảnh gốc.'];
        }

        // Create thumbnail for admin (300x200px)
        $thumbnailPath = null;
        if ($createThumbnail) {
            $thumbnailFilename = 'thumb_' . $baseFilename;
            $thumbnailPath = $uploadDir . $thumbnailFilename;
            if (extension_loaded('gd')) {
                resizeImage($originalPath, $thumbnailPath, 300, 200, 85);
            } else {
                // If GD not available, just copy original
                copy($originalPath, $thumbnailPath);
            }
        }

        // Note: Original image is kept at full size for frontend display
        // Only thumbnail is resized for admin preview

        return [
            'success' => true, 
            'original' => '/uploads/about/' . $baseFilename,
            'thumbnail' => $thumbnailPath ? '/uploads/about/' . basename($thumbnailPath) : null
        ];
    }

    public function saveSettings()
    {
        $data = [];
        $settings = About::getSettings();
        $tab = isset($_POST['tab']) ? sanitizeInput($_POST['tab']) : 'settings';
        
        // Handle banner image upload
        if (!empty($_FILES['banner_image']) && $_FILES['banner_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['banner_image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($file['type'], $allowed)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'about_banner_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $uploadDir = BASE_PATH . 'public/uploads/about/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $tempTarget = $uploadDir . 'temp_' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $tempTarget)) {
                        $result = $this->processImageUpload($tempTarget, $filename, $uploadDir, 1920, 1080, 90, true);
                        if ($result['success']) {
                            $data['banner_image'] = $result['original'];
                            $data['banner_image_thumb'] = $result['thumbnail'];
                        } else {
                            Session::setFlash('error', 'Ảnh Banner: ' . $result['message']);
                            unlink($tempTarget);
                            header('Location: /admin/about?tab=' . $tab);
                            exit;
                        }
                        if (file_exists($tempTarget)) unlink($tempTarget);
                    }
                }
            }
        } else if (!empty($settings['banner_image'])) {
            $data['banner_image'] = $settings['banner_image'];
            $data['banner_image_thumb'] = $settings['banner_image_thumb'] ?? null;
        }

        // Handle vision image upload
        if (!empty($_FILES['vision_image']) && $_FILES['vision_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['vision_image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($file['type'], $allowed)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'about_vision_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $uploadDir = BASE_PATH . 'public/uploads/about/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $tempTarget = $uploadDir . 'temp_' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $tempTarget)) {
                        $result = $this->processImageUpload($tempTarget, $filename, $uploadDir, 1920, 1080, 90, true);
                        if ($result['success']) {
                            $data['vision_image'] = $result['original'];
                            $data['vision_image_thumb'] = $result['thumbnail'];
                        } else {
                            Session::setFlash('error', 'Ảnh Tầm nhìn - Sứ mệnh - Giá trị: ' . $result['message']);
                            unlink($tempTarget);
                            header('Location: /admin/about?tab=' . $tab);
                            exit;
                        }
                        if (file_exists($tempTarget)) unlink($tempTarget);
                    }
                }
            }
        } else if (!empty($settings['vision_image'])) {
            $data['vision_image'] = $settings['vision_image'];
            $data['vision_image_thumb'] = $settings['vision_image_thumb'] ?? null;
        }

        // Text content
        if (isset($_POST['intro_content'])) $data['intro_content'] = $_POST['intro_content'];
        if (isset($_POST['vision_content'])) $data['vision_content'] = $_POST['vision_content'];
        if (isset($_POST['mission_content'])) $data['mission_content'] = $_POST['mission_content'];
        if (isset($_POST['values_content'])) $data['values_content'] = $_POST['values_content'];
        if (isset($_POST['decor_content'])) $data['decor_content'] = $_POST['decor_content'];
        
        // Statistics
        if (isset($_POST['customers_target'])) $data['customers_target'] = (int) $_POST['customers_target'];
        if (isset($_POST['years_target'])) $data['years_target'] = (int) $_POST['years_target'];
        if (isset($_POST['projects_target'])) $data['projects_target'] = (int) $_POST['projects_target'];
        if (isset($_POST['loyal_customers_target'])) $data['loyal_customers_target'] = (int) $_POST['loyal_customers_target'];

        $ok = About::updateSettings($data);
        Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật cài đặt thành công.' : 'Cập nhật thất bại.');
        header('Location: /admin/about?tab=' . $tab);
        exit;
    }

    public function saveDecorImage()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $data = [];
        $tab = isset($_POST['tab']) ? sanitizeInput($_POST['tab']) : 'decor-images';
        
        $displayOrder = isset($_POST['display_order']) ? (int) $_POST['display_order'] : 0;
        
        // Validate display_order không trùng (trừ chính nó nếu đang edit)
        $existingImages = About::getDecorImages();
        foreach ($existingImages as $img) {
            if ($img['id'] != $id && $img['display_order'] == $displayOrder) {
                Session::setFlash('error', 'Thứ tự ' . $displayOrder . ' đã tồn tại. Vui lòng nhập lại.');
                header('Location: /admin/about?tab=' . $tab);
                exit;
            }
        }
        
        if (isset($_POST['display_order'])) {
            $data['display_order'] = $displayOrder;
        }

        // Handle image upload
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['image'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (in_array($file['type'], $allowed)) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'about_decor_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $uploadDir = BASE_PATH . 'public/uploads/about/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $tempTarget = $uploadDir . 'temp_' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $tempTarget)) {
                        $result = $this->processImageUpload($tempTarget, $filename, $uploadDir, 3840, 2160, 92, true);
                        if ($result['success']) {
                            $data['image_url'] = $result['original'];
                            $data['image_url_thumb'] = $result['thumbnail'];
                        } else {
                            Session::setFlash('error', 'Ảnh nội thất trang trí: ' . $result['message']);
                            unlink($tempTarget);
                            header('Location: /admin/about?tab=' . $tab);
                            exit;
                        }
                        if (file_exists($tempTarget)) unlink($tempTarget);
                    }
                }
            }
        } else if ($id) {
            // Giữ nguyên ảnh cũ nếu không upload mới
            $image = About::getDecorImageById($id);
            if ($image && !empty($image['image_url'])) {
                $data['image_url'] = $image['image_url'];
                $data['image_url_thumb'] = $image['image_url_thumb'] ?? null;
            } else {
                Session::setFlash('error', 'Vui lòng chọn ảnh.');
                header('Location: /admin/about?tab=' . $tab);
                exit;
            }
        }

        if ($id) {
            if (!empty($data)) {
                $ok = About::updateDecorImage($id, $data);
                Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật ảnh thành công.' : 'Cập nhật thất bại.');
            }
        } else {
            if (!empty($data['image_url'])) {
                $newId = About::createDecorImage($data['image_url'], $displayOrder);
                if ($newId && !empty($data['image_url_thumb'])) {
                    About::updateDecorImage($newId, ['image_url_thumb' => $data['image_url_thumb']]);
                }
                Session::setFlash($newId ? 'success' : 'error', $newId ? 'Thêm ảnh thành công.' : 'Thêm thất bại.');
            } else {
                Session::setFlash('error', 'Vui lòng chọn ảnh.');
            }
        }

        header('Location: /admin/about?tab=' . $tab);
        exit;
    }

    public function deleteDecorImage()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $tab = isset($_POST['tab']) ? sanitizeInput($_POST['tab']) : 'decor-images';
        if ($id) {
            $ok = About::deleteDecorImage($id);
            Session::setFlash($ok ? 'success' : 'error', $ok ? 'Xóa ảnh thành công.' : 'Xóa thất bại.');
        }
        header('Location: /admin/about?tab=' . $tab);
        exit;
    }

    public function saveAdvantage()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $content = trim($_POST['content'] ?? '');
        $displayOrder = isset($_POST['display_order']) ? (int) $_POST['display_order'] : 0;
        $tab = isset($_POST['tab']) ? sanitizeInput($_POST['tab']) : 'advantages';

        if (empty($content)) {
            Session::setFlash('error', 'Nội dung không được để trống.');
            header('Location: /admin/about?tab=' . $tab);
            exit;
        }

        // Validate display_order không trùng (trừ chính nó nếu đang edit)
        $existingAdvantages = About::getAdvantages();
        foreach ($existingAdvantages as $adv) {
            if ($adv['id'] != $id && $adv['display_order'] == $displayOrder) {
                Session::setFlash('error', 'Thứ tự ' . $displayOrder . ' đã tồn tại. Vui lòng nhập lại.');
                header('Location: /admin/about?tab=' . $tab);
                exit;
            }
        }

        if ($id) {
            $ok = About::updateAdvantage($id, ['content' => $content, 'display_order' => $displayOrder]);
            Session::setFlash($ok ? 'success' : 'error', $ok ? 'Cập nhật ưu thế thành công.' : 'Cập nhật thất bại.');
        } else {
            $newId = About::createAdvantage($content, $displayOrder);
            Session::setFlash($newId ? 'success' : 'error', $newId ? 'Thêm ưu thế thành công.' : 'Thêm thất bại.');
        }

        header('Location: /admin/about?tab=' . $tab);
        exit;
    }

    public function deleteAdvantage()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $tab = isset($_POST['tab']) ? sanitizeInput($_POST['tab']) : 'advantages';
        if ($id) {
            $ok = About::deleteAdvantage($id);
            Session::setFlash($ok ? 'success' : 'error', $ok ? 'Xóa ưu thế thành công.' : 'Xóa thất bại.');
        }
        header('Location: /admin/about?tab=' . $tab);
        exit;
    }
}
