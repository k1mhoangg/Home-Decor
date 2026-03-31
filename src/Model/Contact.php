<?php


namespace Model;

use \Core\Database;
use \PDO;
use \Exception;

class Contact
{
    private $id;
    private $name;
    private $email;
    private $phone;
    private $subject;
    private $message;
    private $status;
    private $created_at;

    public function __construct($name, $email, $message, $phone = null, $subject = null, $status = 'pending', $id = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->subject = $subject;
        $this->message = $message;
        $this->status = $status;
        $this->created_at = $created_at;
    }

    // ...getters...
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function getSubject()
    {
        return $this->subject;
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function save()
    {
        $db = Database::getInstance()->getConnection();
        // Sanitization
        $this->name = filter_var($this->name, FILTER_SANITIZE_STRING);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->message = filter_var($this->message, FILTER_SANITIZE_STRING);
        $this->phone = $this->phone ? filter_var($this->phone, FILTER_SANITIZE_STRING) : null;
        $this->subject = $this->subject ? filter_var($this->subject, FILTER_SANITIZE_STRING) : null;

        $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message, status) VALUES (:name, :email, :phone, :subject, :message, :status)");
        return $stmt->execute([
            ':name' => $this->name,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':subject' => $this->subject,
            ':message' => $this->message,
            ':status' => $this->status
        ]);
    }

    // fetch all contacts (optionally order/limit)
    public static function getAll($orderBy = 'created_at DESC', int $limit = 0, int $offset = 0)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM contacts ORDER BY {$orderBy}";
        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $db->query($sql);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count(): int
    {
        $db = Database::getInstance()->getConnection();
        return (int) $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    }

    public static function findById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row)
            return null;
        return new self($row['name'], $row['email'], $row['message'], $row['phone'] ?? null, $row['subject'] ?? null, $row['status'] ?? 'pending', $row['id'], $row['created_at'] ?? null);
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE contacts SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public static function markReplied(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE contacts SET status = 'replied', replied_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function deleteById(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM contacts WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function recent(int $limit = 5)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM contacts ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function test()
    {
        echo "<div>Contact model test method called.</div>";
    }
}