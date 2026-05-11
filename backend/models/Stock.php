<?php
class Stock {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(): array {
        $stmt = $this->db->query(
            'SELECT s.*, p.name, p.category, p.price, p.image_url 
             FROM stock s 
             JOIN products p ON p.id = s.product_id 
             WHERE p.active = 1
             ORDER BY s.quantity ASC'
        );
        return $stmt->fetchAll();
    }

    public function findByProduct(int $productId): ?array {
        $stmt = $this->db->prepare('SELECT * FROM stock WHERE product_id = ?');
        $stmt->execute([$productId]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $productId, int $quantity, int $minAlert): void {
        $stmt = $this->db->prepare(
            'UPDATE stock SET quantity = ?, min_alert = ? WHERE product_id = ?'
        );
        $stmt->execute([$quantity, $minAlert, $productId]);
    }

    public function decrease(int $productId, int $quantity): void {
        $stmt = $this->db->prepare(
            'UPDATE stock SET quantity = quantity - ? WHERE product_id = ? AND quantity >= ?'
        );
        $stmt->execute([$quantity, $productId, $quantity]);
    }

    public function getLowStock(): array {
        $stmt = $this->db->query(
            'SELECT s.*, p.name, p.category 
             FROM stock s 
             JOIN products p ON p.id = s.product_id 
             WHERE s.quantity <= s.min_alert AND p.active = 1'
        );
        return $stmt->fetchAll();
    }
}