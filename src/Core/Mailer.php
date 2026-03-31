<?php

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Mailer
{
    private $mailer;
    private $lastError = null;

    public function __construct()
    {
        require_once BASE_PATH . 'vendor/autoload.php';

        $this->mailer = new PHPMailer(true);

        // lấy cấu hình từ environment (use app password for EMAIL_PASS)
        $host = getenv('EMAIL_HOST') ?: 'smtp.gmail.com';
        $port = getenv('EMAIL_PORT') ?: 587;
        $user = getenv('EMAIL_USER') ?: '';
        $pass = getenv('EMAIL_PASS') ?: '';
        $from = getenv('EMAIL_FROM') ?: $user;
        $fromName = getenv('EMAIL_FROM_NAME') ?: 'HomeDecor Support';
        $encryption = strtolower(getenv('EMAIL_ENCRYPTION') ?: 'tls');
        $debug = getenv('EMAIL_DEBUG') ?: 0;

        // chung
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->SMTPDebug = (int) $debug; // Set to 2 for debugging
        $this->mailer->Timeout = 30;


        if (!empty($host) && !empty($user)) {
            $this->mailer->isSMTP();
            $this->mailer->Host = $host;
            $this->mailer->Port = (int) $port;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $user;
            $this->mailer->Password = $pass;

            if ($encryption === 'ssl') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            // Thêm options SSL để tránh lỗi certificate verification
            $this->mailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
        } else {
            // Fallback to PHP mail() function
            $this->mailer->isMail();
        }

        try {
            if (!empty($from)) {
                $this->mailer->setFrom($from, $fromName);
            }
        } catch (\Throwable $e) {
            $this->lastError = 'setFrom failed: ' . $e->getMessage();
            error_log('Mailer: ' . $this->lastError);
        }
    }

    public function send(string $to, string $subject, string $htmlBody, string $altBody = null): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->clearReplyTos();

            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->isHTML(true);
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $altBody ?? strip_tags($htmlBody);

            $sent = (bool) $this->mailer->send();
            if ($sent) {
                $this->lastError = null;
            }
            return $sent;
        } catch (PHPMailerException $e) {
            $this->lastError = $e->getMessage();
            error_log('PHPMailer error: ' . $e->getMessage());
            return false;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            error_log('Mailer unexpected error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendReplyToUser(string $userEmail, string $userName, string $replyMessage, string $originalMessage = null, string $subject = null): bool
    {
        $subject = $subject ?: 'Phản hồi từ HomeDecor';
        $userNameSafe = htmlspecialchars($userName ?: 'Quý khách');
        $replySafe = nl2br(htmlspecialchars($replyMessage));
        $origSafe = $originalMessage ? nl2br(htmlspecialchars($originalMessage)) : '';

        $html = "<p>Xin chào {$userNameSafe},</p>";
        $html .= "<div>{$replySafe}</div>";
        if ($origSafe !== '') {
            $html .= "<hr><p><strong>Nội dung bạn đã gửi:</strong></p><div>{$origSafe}</div>";
        }
        $html .= "<br><p>Trân trọng,<br>HomeDecor Support</p>";

        $alt = strip_tags($replyMessage . "\n\n---\n\n" . ($originalMessage ?? ''));

        return $this->send($userEmail, $subject, $html, $alt);
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }
}