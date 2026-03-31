<?php
namespace Controller\Admin;

use Model\News;

class NewsController
{
    public function index()
    {
        $newsList = News::all();
        require BASE_PATH . 'view/admin/news.php';
    }

    public function edit($id = null)
    {
        if ($id) {
            $news = News::findById($id);
            if (!$news) {
                http_response_code(404);
                echo 'Bài viết không tồn tại.';
                return;
            }
        } else {
            $news = [];
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'thumbnail' => trim($_POST['thumbnail'] ?? ''), // fallback nếu không upload mới
                'content' => trim($_POST['content'] ?? ''),
                'is_published' => isset($_POST['is_published']) ? (int)$_POST['is_published'] : 1
            ];
            // Xử lý upload file ảnh đại diện nếu có (giống sản phẩm)
            if (!empty($_FILES['thumbnail_file']) && $_FILES['thumbnail_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['thumbnail_file'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (in_array($file['type'], $allowed)) {
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $filename = 'news_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                        $uploadDir = BASE_PATH . 'public/uploads/news/';
                        if (!is_dir($uploadDir))
                            mkdir($uploadDir, 0755, true);
                        $target = $uploadDir . $filename;
                        if (move_uploaded_file($file['tmp_name'], $target)) {
                            $data['thumbnail'] = 'uploads/news/' . $filename;
                        }
                    }
                }
            }
            if ($id) {
                News::update($id, $data);
            } else {
                News::create($data);
            }
            header('Location: /admin/news');
            exit;
        }
        require BASE_PATH . 'view/admin/news_edit.php';
    }

    public function delete($id)
    {
        News::delete($id);
        header('Location: /admin/news');
        exit;
    }

    public function comments($newsId)
    {
        $news = News::findById($newsId);
        if (!$news) {
            http_response_code(404);
            echo 'Bài viết không tồn tại.';
            return;
        }
        $comments = News::getComments($newsId);
        require BASE_PATH . 'view/admin/news_comments.php';
    }

    public function deleteComment($newsId = null, $commentId = null)
    {
        if (!$commentId && $newsId) { // gọi từ route cũ
            $commentId = $newsId;
            $newsId = null;
        }
        \Model\News::deleteComment($commentId);
        if ($newsId) {
            header('Location: /admin/news/' . $newsId . '/comments');
        } else {
            header('Location: /admin/news/comments');
        }
        exit;
    }
    

    // Trang quản lý tất cả bình luận bài viết
    public function allComments()
    {
        $comments = \Model\News::getAllComments();
        require BASE_PATH . 'view/admin/news_all_comments.php';
    }
}
