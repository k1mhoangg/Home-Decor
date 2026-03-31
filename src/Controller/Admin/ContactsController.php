<?php
namespace Controller\Admin;

use Model\Contact;
use Core\Session;
use Core\Pagination;
use Core\Mailer;

class ContactsController extends BaseAdminController
{
    public function index()
    {
        $perPage = 10;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $total = Contact::count();
        $pagination = new Pagination($total, $perPage, $page);

        $contacts = Contact::getAll('created_at DESC', $pagination->getLimit(), $pagination->getOffset());

        require_once BASE_PATH . 'view/admin/contacts.php';
    }

    public function view()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $contact = Contact::findById($id);
        if (!$contact) {
            Session::setFlash('error', 'Liên hệ không tồn tại.');
            header('Location: /admin/contacts');
            exit;
        }
        require_once BASE_PATH . 'view/admin/contact_view.php';
    }

    public function updateStatus()
    {
        $id = (int) ($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        if ($id && in_array($status, ['pending', 'replied', 'closed', 'read', 'unread'])) {
            Contact::updateStatus($id, $status);
            Session::setFlash('success', 'Cập nhật trạng thái thành công.');
        } else {
            Session::setFlash('error', 'Dữ liệu không hợp lệ.');
        }
        header('Location: /admin/contacts');
        exit;
    }

    public function delete()
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id && Contact::deleteById($id)) {
            Session::setFlash('success', 'Xóa liên hệ thành công.');
        } else {
            Session::setFlash('error', 'Xóa thất bại.');
        }
        header('Location: /admin/contacts');
        exit;
    }

    public function reply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/contacts');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $replyMessage = trim($_POST['reply_message'] ?? '');

        $contact = Contact::findById($id);
        if (!$contact) {
            Session::setFlash('error', 'Liên hệ không tồn tại.');
            header('Location: /admin/contacts');
            exit;
        }

        $toEmail = $contact->getEmail();
        if (empty($toEmail)) {
            Session::setFlash('error', 'Địa chỉ email người gửi không tồn tại.');
            header('Location: /admin/contacts/view?id=' . $id);
            exit;
        }
        // Sử dụng Core\Mailer
        $mailer = new Mailer();
        $sent = $mailer->sendReplyToUser(
            $toEmail,
            $contact->getName(),
            $replyMessage,
            $contact->getMessage()
        );


        if ($sent) {
            Contact::markReplied($id);
            Session::setFlash('success', 'Gửi trả lời thành công.');
        } else {
            $err = $mailer->getLastError() ?: 'Không thể gửi email.';
            Session::setFlash('error', 'Gửi email thất bại: ' . $err);
        }

        header('Location: /admin/contacts/view?id=' . $id);
        exit;
    }
}
