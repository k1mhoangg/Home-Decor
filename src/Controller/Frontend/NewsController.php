<?php
namespace Controller\Frontend;

use Model\News;

class NewsController
{
    public function index()
    {
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($search !== '') {
            $newsList = News::searchByTitle($search);
        } else {
            $newsList = News::all();
        }
        require BASE_PATH . 'view/frontend/news/index.php';
    }

    public function detail($id)
    {
        $news = News::findById($id);
        if (!$news) {
            http_response_code(404);
            require BASE_PATH . 'view/frontend/404.php';
            return;
        }
        $comments = News::getComments($id);
        require BASE_PATH . 'view/frontend/news/detail.php';
    }

    public function addComment($id)
    {
        \Core\Session::start();
        $response = ['success' => false, 'message' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_SESSION['user'] ?? null;
            $content = trim($_POST['content'] ?? '');
            if ($user && $content) {
                $ok = News::addComment($id, $user['id'], $content);
                if ($ok) {
                    $response['success'] = true;
                    $response['author'] = $user['username'];
                    $response['content'] = $content;
                    $response['created_at'] = date('Y-m-d H:i:s');
                } else {
                    $response['message'] = 'Không thể lưu bình luận.';
                }
            } else {
                $response['message'] = $user ? 'Nội dung không được để trống.' : 'Bạn cần đăng nhập để bình luận.';
            }
        } else {
            $response['message'] = 'Yêu cầu không hợp lệ.';
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
