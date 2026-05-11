<?php
class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByUser(int $userId): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findAll(): array {
        $stmt = $this->db->query(
            'SELECT o.*, u.name as user_name, u.email as user_email 
             FROM orders o 
             JOIN users u ON u.id = o.user_id 
             ORDER BY o.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare(
            'SELECT oi.*, p.name, p.image_url FROM order_items oi 
             JOIN products p ON p.id = oi.product_id 
             WHERE oi.order_id = ?'
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function create(int $userId, float $total, string $currency, float $totalLocal): int {
        $stmt = $this->db->prepare(
            'INSERT INTO orders (user_id, total_usd, currency, total_local, status) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $total, $currency, $totalLocal, 'pending']);
        return (int) $this->db->lastInsertId();
    }

    public function addItem(int $orderId, int $productId, int $qty, float $price): void {
        $stmt = $this->db->prepare(
            'INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$orderId, $productId, $qty, $price]);
    }

    public function updateStatus(int $id, string $status): void {
        $stmt = $this->db->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }
}