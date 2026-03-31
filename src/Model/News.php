<?php
namespace Model;

use \Core\Database;
use PDO;

class News
{
    public static function all($limit = 0, $offset = 0)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM news ORDER BY created_at DESC";
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

    public static function findById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM news WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getComments($newsId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT nc.*, u.username as author FROM news_comments nc LEFT JOIN users u ON nc.user_id = u.id WHERE nc.news_id = :news_id ORDER BY nc.created_at ASC");
        $stmt->execute([':news_id' => $newsId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addComment($newsId, $userId, $content)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO news_comments (news_id, user_id, comment, created_at) VALUES (:news_id, :user_id, :comment, NOW())");
        return $stmt->execute([
            ':news_id' => $newsId,
            ':user_id' => $userId,
            ':comment' => $content
        ]);
    }
    
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO news (title, category, thumbnail, content, is_published, created_at, updated_at) VALUES (:title, :category, :thumbnail, :content, :is_published, NOW(), NOW())");
        $stmt->execute([
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':thumbnail' => $data['thumbnail'],
            ':content' => $data['content'],
            ':is_published' => $data['is_published'] ?? 1
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE news SET title = :title, category = :category, thumbnail = :thumbnail, content = :content, is_published = :is_published, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':thumbnail' => $data['thumbnail'],
            ':content' => $data['content'],
            ':is_published' => $data['is_published'] ?? 1,
            ':id' => $id
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM news WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function deleteComment($commentId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM news_comments WHERE id = :id");
        return $stmt->execute([':id' => $commentId]);
    }
    

    // Lấy tất cả bình luận của mọi bài viết (cho admin)
    public static function getAllComments()
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT nc.*, u.username as author, n.title as news_title FROM news_comments nc
                LEFT JOIN users u ON nc.user_id = u.id
                LEFT JOIN news n ON nc.news_id = n.id
                ORDER BY nc.created_at DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        // Tìm kiếm tin tức theo tiêu đề
    public static function searchByTitle($title, $limit = 0, $offset = 0)
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM news WHERE title LIKE :title ORDER BY created_at DESC";
        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':title', '%' . $title . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':title', '%' . $title . '%', PDO::PARAM_STR);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
