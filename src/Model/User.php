<?php

namespace Model;

use \Core\Database;
use \PDO;
use \Exception;

class User
{
    private $id;
    private $username;
    private $password; // hashed or plain depending on constructor usage
    private $role;
    private $display_name;
    private $email;
    private $avatar;

    public function __construct($username, $password, $role = 'user', $id = null, $display_name = null, $email = null, $avatar = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
        $this->id = $id;
        $this->display_name = $display_name;
        $this->email = $email;
        $this->avatar = $avatar;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getDisplayName()
    {
        return $this->display_name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }

    // Save a new user (returns true on success)
    public function save()
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $this->username]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new Exception('Username already exists');
        }

        if (!password_get_info($this->password)['algo']) {
            $hashed = password_hash($this->password, PASSWORD_DEFAULT);
        } else {
            $hashed = $this->password;
        }

        $stmt = $db->prepare("INSERT INTO users (username, password, role, display_name, email, avatar) VALUES (:username, :password, :role, :display_name, :email, :avatar)");
        return $stmt->execute([
            ':username' => $this->username,
            ':password' => $hashed,
            ':role' => $this->role,
            ':display_name' => $this->display_name,
            ':email' => $this->email,
            ':avatar' => $this->avatar
        ]);
    }

    // Static method to create new user
    public static function create(array $data)
    {
        $db = Database::getInstance()->getConnection();

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'customer';
        $display_name = $data['display_name'] ?? null;
        $email = $data['email'] ?? null;
        $avatar = $data['avatar'] ?? null;

        if (empty($username) || empty($password)) {
            throw new Exception('Username and password are required');
        }

        // Check if username exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new Exception('Username already exists');
        }

        // Hash password if not already hashed
        if (!password_get_info($password)['algo']) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Insert new user
        $stmt = $db->prepare("INSERT INTO users (username, password, role, display_name, email, avatar) VALUES (:username, :password, :role, :display_name, :email, :avatar)");
        $success = $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':role' => $role,
            ':display_name' => $display_name,
            ':email' => $email,
            ':avatar' => $avatar
        ]);

        return $success ? $db->lastInsertId() : false;
    }

    // Find a user record by username, return User instance or null
    public static function findByUsername(string $username)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, username, password, role, display_name, email, avatar FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row)
            return null;
        return new self($row['username'], $row['password'], $row['role'], $row['id'], $row['display_name'] ?? null, $row['email'] ?? null, $row['avatar'] ?? null);
    }

    // Find by id
    public static function findById(int $id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, username, password, role, display_name, email, avatar FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row)
            return null;
        return new self($row['username'], $row['password'], $row['role'], $row['id'], $row['display_name'] ?? null, $row['email'] ?? null, $row['avatar'] ?? null);
    }

    // Verify a plain password against stored hash
    public function verifyPassword(string $plain): bool
    {
        return password_verify($plain, $this->password);
    }

    // Update arbitrary columns for a given user id. $data is associative array column => value
    public static function updateById(int $id, array $data): bool
    {
        if (empty($data))
            return false;
        $allowed = ['username', 'password', 'role', 'display_name', 'email', 'avatar'];
        $sets = [];
        $params = [':id' => $id];
        foreach ($data as $k => $v) {
            if (!in_array($k, $allowed))
                continue;
            // if updating password, hash it
            if ($k === 'password' && !password_get_info($v)['algo']) {
                $v = password_hash($v, PASSWORD_DEFAULT);
            }
            $param = ':' . $k;
            $sets[] = "`$k` = $param";
            $params[$param] = $v;
        }
        if (empty($sets))
            return false;
        $sql = "UPDATE users SET " . implode(', ', $sets) . " WHERE id = :id";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function deleteById(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }


    public static function all(int $limit = 0, int $offset = 0): array
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT id, username, password, role, display_name, email, avatar FROM users";
        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $db->query($sql);
        }
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new self($row['username'], $row['password'], $row['role'], $row['id'], $row['display_name'] ?? null, $row['email'] ?? null, $row['avatar'] ?? null);
        }
        return $users;
    }

    public static function count(): int
    {
        $db = Database::getInstance()->getConnection();
        return (int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public static function recent(int $limit = 5): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}