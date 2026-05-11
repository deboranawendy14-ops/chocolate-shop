<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password_hash'],
            $data['role'] ?? 'customer'
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateResetToken(string $email, string $token): void {
        $stmt = $this->db->prepare(
            'UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?'
        );
        $stmt->execute([$token, date('Y-m-d H:i:s', time() + 3600), $email]);
    }

    public function findByResetToken(string $token): ?array {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()'
        );
        $stmt->execute([$token]);
        return $stmt->fetch() ?: null;
    }

    public function updatePassword(int $id, string $hash): void {
        $stmt = $this->db->prepare(
            'UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?'
        );
        $stmt->execute([$hash, $id]);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }
}