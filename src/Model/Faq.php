<?php

namespace Model;

use \Core\Database;
use \PDO;
use \Exception;

class Faq
{
    private $id;
    private $question;
    private $answer;
    private $user_id;
    private $is_published;
    private $created_at;
    private $updated_at;

    public function __construct($question, $answer, $user_id = null, $is_published = 0, $id = null, $created_at = null, $updated_at = null)
    {
        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;
        $this->user_id = $user_id;
        $this->is_published = $is_published;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getIsPublished()
    {
        return $this->is_published;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    // Save new FAQ (for user submissions)
    public function save()
    {
        $db = Database::getInstance()->getConnection();

        // Sanitization
        $this->question = sanitizeInput($this->question);
        $this->answer = $this->answer ? sanitizeInput($this->answer) : null;

        $stmt = $db->prepare("INSERT INTO faqs (question, answer, user_id, is_published) VALUES (:question, :answer, :user_id, :is_published)");
        return $stmt->execute([
            ':question' => $this->question,
            ':answer' => $this->answer,
            ':user_id' => $this->user_id,
            ':is_published' => $this->is_published
        ]);
    }

    // Get all published FAQs with search and pagination
    public static function getAll($search = '', int $limit = 0, int $offset = 0)
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM faqs WHERE is_published = 1";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
        }

        $sql .= " ORDER BY created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }

        if ($limit > 0) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $faqs = [];
        foreach ($rows as $row) {
            $faqs[] = new self(
                $row['question'],
                $row['answer'],
                $row['user_id'] ?? null,
                $row['is_published'],
                $row['id'],
                $row['created_at'] ?? null,
                $row['updated_at'] ?? null
            );
        }

        return $faqs;
    }

    // Count published FAQs with search
    public static function count($search = ''): int
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) FROM faqs WHERE is_published = 1";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $stmt = $db->query($sql);
        }

        return (int) $stmt->fetchColumn();
    }

    // Get all FAQs by user_id with search and pagination
    public static function getByUserId(int $user_id, $search = '', int $limit = 0, int $offset = 0)
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM faqs WHERE user_id = :user_id";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
        }

        $sql .= " ORDER BY created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if (!empty($search)) {
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }

        if ($limit > 0) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $faqs = [];
        foreach ($rows as $row) {
            $faqs[] = new self(
                $row['question'],
                $row['answer'],
                $row['user_id'] ?? null,
                $row['is_published'],
                $row['id'],
                $row['created_at'] ?? null,
                $row['updated_at'] ?? null
            );
        }

        return $faqs;
    }

    // Count FAQs by user_id with search
    public static function countByUserId(int $user_id, $search = ''): int
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) FROM faqs WHERE user_id = :user_id";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        return (int) $stmt->fetchColumn();
    }

    public static function findById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM faqs WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new self(
            $row['question'],
            $row['answer'],
            $row['user_id'] ?? null,
            $row['is_published'],
            $row['id'],
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }

    // Admin methods
    // Get published FAQs (is_published = 1 and has answer)
    public static function getPublished($search = '', int $limit = 0, int $offset = 0)
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM faqs WHERE is_published = 1 AND answer IS NOT NULL AND answer != ''";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
        }

        $sql .= " ORDER BY updated_at DESC, created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }

        if ($limit > 0) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $faqs = [];
        foreach ($rows as $row) {
            $faqs[] = new self(
                $row['question'],
                $row['answer'],
                $row['user_id'] ?? null,
                $row['is_published'],
                $row['id'],
                $row['created_at'] ?? null,
                $row['updated_at'] ?? null
            );
        }

        return $faqs;
    }

    // Count published FAQs
    public static function countPublished($search = ''): int
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) FROM faqs WHERE is_published = 1 AND answer IS NOT NULL AND answer != ''";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $stmt = $db->query($sql);
        }

        return (int) $stmt->fetchColumn();
    }

    // Get unpublished FAQs (is_published = 0 and has answer)
    public static function getUnpublished($search = '', int $limit = 0, int $offset = 0)
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM faqs WHERE is_published = 0 AND answer IS NOT NULL AND answer != ''";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
        }

        $sql .= " ORDER BY updated_at DESC, created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }

        if ($limit > 0) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $faqs = [];
        foreach ($rows as $row) {
            $faqs[] = new self(
                $row['question'],
                $row['answer'],
                $row['user_id'] ?? null,
                $row['is_published'],
                $row['id'],
                $row['created_at'] ?? null,
                $row['updated_at'] ?? null
            );
        }

        return $faqs;
    }

    // Count unpublished FAQs
    public static function countUnpublished($search = ''): int
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) FROM faqs WHERE is_published = 0 AND answer IS NOT NULL AND answer != ''";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $stmt = $db->query($sql);
        }

        return (int) $stmt->fetchColumn();
    }

    // Get unanswered FAQs (answer IS NULL or empty)
    public static function getUnanswered($search = '', int $limit = 0, int $offset = 0)
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM faqs WHERE answer IS NULL OR answer = ''";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
        }

        $sql .= " ORDER BY created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }

        if ($limit > 0) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $faqs = [];
        foreach ($rows as $row) {
            $faqs[] = new self(
                $row['question'],
                $row['answer'],
                $row['user_id'] ?? null,
                $row['is_published'],
                $row['id'],
                $row['created_at'] ?? null,
                $row['updated_at'] ?? null
            );
        }

        return $faqs;
    }

    // Count unanswered FAQs
    public static function countUnanswered($search = ''): int
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) FROM faqs WHERE answer IS NULL OR answer = ''";

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $sql .= " AND question LIKE :search";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $stmt = $db->query($sql);
        }

        return (int) $stmt->fetchColumn();
    }

    // Update FAQ (instance method)
    public function update()
    {
        $db = Database::getInstance()->getConnection();

        // Sanitization
        $question = sanitizeInput($this->question);
        $answer = $this->answer ? sanitizeInput($this->answer) : null;

        $stmt = $db->prepare("UPDATE faqs SET question = :question, answer = :answer, is_published = :is_published, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([
            ':question' => $question,
            ':answer' => $answer,
            ':is_published' => $this->is_published,
            ':id' => $this->id
        ]);
    }

    // Static update method for admin
    public static function updateById(int $id, string $question, string $answer, int $is_published): bool
    {
        $db = Database::getInstance()->getConnection();

        // Sanitization
        $question = sanitizeInput($question);
        $answer = !empty($answer) ? sanitizeInput($answer) : null;

        $stmt = $db->prepare("UPDATE faqs SET question = :question, answer = :answer, is_published = :is_published, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([
            ':question' => $question,
            ':answer' => $answer,
            ':is_published' => $is_published,
            ':id' => $id
        ]);
    }

    // Delete FAQ
    public static function deleteById(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM faqs WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Unpublish FAQ (set is_published = 0)
    public static function unpublish(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE faqs SET is_published = 0, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Publish FAQ (set is_published = 1)
    public static function publish(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE faqs SET is_published = 1, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

