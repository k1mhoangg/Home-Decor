<?php
namespace Controller\Admin;

use Model\Faq;
use Core\Session;
use Core\Pagination;

require_once BASE_PATH . 'Core/utils.php';

class FaqsController extends BaseAdminController
{
    public function index()
    {
        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $tab = isset($_GET['tab']) ? sanitizeInput($_GET['tab']) : 'published';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Get active tab
        $activeTab = $tab;
        if (!in_array($activeTab, ['published', 'unpublished', 'unanswered'])) {
            $activeTab = 'published';
        }

        // Get data based on tab
        $faqs = [];
        $pagination = null;
        
        if ($activeTab === 'published') {
            $total = Faq::countPublished($search);
            $pagination = new Pagination($total, $perPage, $page);
            $faqs = Faq::getPublished($search, $pagination->getLimit(), $pagination->getOffset());
        } elseif ($activeTab === 'unpublished') {
            $total = Faq::countUnpublished($search);
            $pagination = new Pagination($total, $perPage, $page);
            $faqs = Faq::getUnpublished($search, $pagination->getLimit(), $pagination->getOffset());
        } else { // unanswered
            $total = Faq::countUnanswered($search);
            $pagination = new Pagination($total, $perPage, $page);
            $faqs = Faq::getUnanswered($search, $pagination->getLimit(), $pagination->getOffset());
        }

        // Pass variables to view
        $activeTab = $activeTab; // Make sure it's available in view
        $search = $search; // Make sure it's available in view

        require_once BASE_PATH . 'view/admin/faq.php';
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $faq = $id ? Faq::findById($id) : null;
        
        if (!$faq) {
            Session::setFlash('error', 'Câu hỏi không tồn tại.');
            header('Location: /admin/faqs');
            exit;
        }

        $isAnswering = empty($faq->getAnswer()); // If no answer, this is for answering

        require_once BASE_PATH . 'view/admin/faq_edit.php';
    }

    public function save()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $question = isset($_POST['question']) ? trim($_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        $is_published = isset($_POST['is_published']) ? (int) $_POST['is_published'] : 0;

        if (empty($question)) {
            Session::setFlash('error', 'Câu hỏi không được để trống.');
            header('Location: /admin/faqs/edit?id=' . $id);
            exit;
        }

        $faq = $id ? Faq::findById($id) : null;
        if (!$faq) {
            Session::setFlash('error', 'Câu hỏi không tồn tại.');
            header('Location: /admin/faqs');
            exit;
        }

        // Update FAQ
        if (Faq::updateById($id, $question, $answer, $is_published)) {
            Session::setFlash('success', 'Cập nhật thành công.');
        } else {
            Session::setFlash('error', 'Cập nhật thất bại.');
        }

        // Redirect based on is_published
        if ($is_published) {
            header('Location: /admin/faqs?tab=published');
        } else {
            header('Location: /admin/faqs?tab=unpublished');
        }
        exit;
    }

    public function unpublish()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        
        if ($id && Faq::unpublish($id)) {
            Session::setFlash('success', 'Đã chuyển sang chưa hiển thị.');
        } else {
            Session::setFlash('error', 'Thao tác thất bại.');
        }
        
        header('Location: /admin/faqs?tab=unpublished');
        exit;
    }

    public function publish()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        
        if ($id && Faq::publish($id)) {
            Session::setFlash('success', 'Đã chuyển sang đã hiển thị.');
        } else {
            Session::setFlash('error', 'Thao tác thất bại.');
        }
        
        header('Location: /admin/faqs?tab=published');
        exit;
    }

    public function delete()
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $tab = isset($_POST['tab']) ? sanitizeInput($_POST['tab']) : 'published';
        
        if ($id && Faq::deleteById($id)) {
            Session::setFlash('success', 'Xóa thành công.');
        } else {
            Session::setFlash('error', 'Xóa thất bại.');
        }
        
        header('Location: /admin/faqs?tab=' . $tab);
        exit;
    }
}

