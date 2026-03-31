<?php

namespace Controller\Frontend;

use Model\Faq;
use Core\Pagination;
use Core\Session;

class FaqController
{
    /**
     * Hiển thị danh sách FAQ với phân trang và tìm kiếm
     */
    public function index()
    {
        // Bắt đầu session nếu chưa bắt đầu
        Session::start();

        // Lấy tab hiện tại (mặc định là 'faq' - câu hỏi thường gặp)
        $tab = sanitizeInput($_GET['tab'] ?? 'faq');
        
        // Lấy dữ liệu tìm kiếm và trang hiện tại từ GET
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        // Chỉ sanitize để hiển thị, không dùng cho SQL (SQL sẽ dùng prepared statement)
        $searchDisplay = sanitizeInput($search);
        
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 5;

        // Lấy user hiện tại
        $currentUser = $_SESSION['user'] ?? null;
        $userId = ($currentUser && isset($currentUser['id'])) ? (int) $currentUser['id'] : null;

        // Xử lý theo tab
        if ($tab === 'my-questions') {
            // Tab 2: Câu hỏi của bản thân (yêu cầu đăng nhập)
            if (!$currentUser || !$userId) {
                // Lưu URL hiện tại để quay lại sau khi đăng nhập
                $currentUrl = '/faq?tab=my-questions';
                if (!empty($search)) {
                    $currentUrl .= '&search=' . urlencode($search);
                }
                if ($page > 1) {
                    $currentUrl .= '&page=' . $page;
                }
                $_SESSION['previous_url'] = $currentUrl;
                
                Session::setFlash('error', 'Vui lòng đăng nhập để xem câu hỏi của bạn.');
                header('Location: /login');
                exit;
            }
            
            // Lấy từ khóa tìm kiếm trước đó từ session
            $previousSearch = isset($_SESSION['faq_my_search']) ? (string) $_SESSION['faq_my_search'] : '';
            
            // Nếu từ khóa tìm kiếm thay đổi → reset về trang 1
            if ((isset($_GET['search']) && trim($search) !== trim($previousSearch)) ||
                (!isset($_GET['search']) && !empty($previousSearch))) {
                $page = 1;
            }
            
            $_SESSION['faq_my_search'] = $search;
            
            // Lấy tổng số FAQ của user (đảm bảo $search là string)
            $searchForQuery = (string) $search;
            $total = Faq::countByUserId($userId, $searchForQuery);
            
            // Khởi tạo phân trang
            $pagination = new Pagination($total, $perPage, $page);
            
            if ($page > $pagination->getTotalPages() && $pagination->getTotalPages() > 0) {
                $page = $pagination->getTotalPages();
                $pagination = new Pagination($total, $perPage, $page);
            }
            
            // Lấy danh sách FAQ của user
            $limit = $pagination->getLimit();
            $offset = $pagination->getOffset();
            $faqs = Faq::getByUserId($userId, $searchForQuery, $limit, $offset);
            $isMyQuestions = true;
            
        } elseif ($tab === 'ask') {
            // Tab 3: Đặt câu hỏi (yêu cầu đăng nhập)
            if (!$currentUser || !$userId) {
                // Lưu URL hiện tại để quay lại sau khi đăng nhập
                $_SESSION['previous_url'] = '/faq?tab=ask';
                
                Session::setFlash('error', 'Vui lòng đăng nhập để đặt câu hỏi.');
                header('Location: /login');
                exit;
            }
            
            // Không cần FAQ list cho tab này
            $faqs = [];
            $pagination = null;
            $isMyQuestions = false;
            
        } else {
            // Tab 1: Câu hỏi thường gặp (mặc định)
            $tab = 'faq';
            
            // Lấy từ khóa tìm kiếm trước đó từ session
            $previousSearch = $_SESSION['faq_search'] ?? '';
            
            // Nếu từ khóa tìm kiếm thay đổi → reset về trang 1
            if ((isset($_GET['search']) && $search !== $previousSearch) ||
                (!isset($_GET['search']) && !empty($previousSearch))) {
                $page = 1;
            }
            
            $_SESSION['faq_search'] = $search;
            
            // Lấy tổng số FAQ thỏa điều kiện tìm kiếm
            $total = Faq::count($search);
            
            // Khởi tạo phân trang
            $pagination = new Pagination($total, $perPage, $page);
            
            if ($page > $pagination->getTotalPages() && $pagination->getTotalPages() > 0) {
                $page = $pagination->getTotalPages();
                $pagination = new Pagination($total, $perPage, $page);
            }
            
            // Lấy danh sách FAQ theo search, offset, limit
            $faqs = Faq::getAll($search, $pagination->getLimit(), $pagination->getOffset());
            $isMyQuestions = false;
        }

        // Truyền dữ liệu ra view
        require_once BASE_PATH . 'view/frontend/faq.php';
    }

    /**
     * Xử lý gửi câu hỏi mới từ người dùng
     */
    public function submit()
    {
        // Chỉ xử lý POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /faq');
            exit;
        }

        // Bắt đầu session
        Session::start();

        // Kiểm tra người dùng đã đăng nhập chưa
        $current = $_SESSION['user'] ?? null;
        if (!$current) {
            // Nếu chưa đăng nhập → lưu flash message và redirect về login
            Session::setFlash('error', 'Vui lòng đăng nhập để gửi câu hỏi.');
            header('Location: /login');
            exit;
        }

        // Lấy nội dung câu hỏi từ POST
        $question = sanitizeInput($_POST['question'] ?? '');

        // Nếu câu hỏi rỗng → thông báo lỗi
        if (empty($question)) {
            Session::setFlash('error', 'Vui lòng nhập câu hỏi.');
            header('Location: /faq?tab=ask');
            exit;
        }

        try {
            // Tạo FAQ mới: (question, answer=null, user_id, is_active=0)
            $faq = new Faq($question, null, (int) $current['id'], 0);

            if ($faq->save()) {
                // Nếu lưu thành công → flash success
                Session::setFlash('success', 'Câu hỏi của bạn đã được gửi thành công. Chúng tôi sẽ xem xét và trả lời sớm nhất có thể.');
            } else {
                // Nếu lưu thất bại → flash error
                Session::setFlash('error', 'Có lỗi xảy ra khi gửi câu hỏi. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            // Nếu có exception → flash error
            Session::setFlash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }

        // Quay về tab đặt câu hỏi
        header('Location: /faq?tab=ask');
        exit;
    }
}
