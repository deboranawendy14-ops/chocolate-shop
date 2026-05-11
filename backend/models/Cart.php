<?php
class Cart {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByUser(int $userId): array {
        $stmt = $this->db->prepare(
            'SELECT c.*, p.name, p.price, p.image_url, p.description, s.quantity as stock
             FROM carts c
             JOIN products p ON p.id = c.product_id
             LEFT JOIN stock s ON s.product_id = c.product_id
             WHERE c.user_id = ?'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function addItem(int $userId, int $productId, int $quantity): void {
        // Verifica se já existe
        $stmt = $this->db->prepare(
            'SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?'
        );
        $stmt->execute([$userId, $productId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $stmt = $this->db->prepare(
                'UPDATE carts SET quantity = quantity + ? WHERE id = ?'
            );
            $stmt->execute([$quantity, $existing['id']]);
        } else {
            $stmt = $this->db->prepare(
                'INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)'
            );
            $stmt->execute([$userId, $productId, $quantity]);
        }
    }

    public function updateItem(int $id, int $userId, int $quantity): void {
        $stmt = $this->db->prepare(
            'UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$quantity, $id, $userId]);
    }

    public function removeItem(int $id, int $userId): void {
        $stmt = $this->db->prepare(
            'DELETE FROM carts WHERE id = ? AND user_id = ?'
        );
        $stmt->execute([$id, $userId]);
    }

    public function clearCart(int $userId): void {
        $stmt = $this->db->prepare('DELETE FROM carts WHERE user_id = ?');
        $stmt->execute([$userId]);
    }

    public function getTotal(int $userId): float {
        $stmt = $this->db->prepare(
            'SELECT SUM(p.price * c.quantity) as total
             FROM carts c
             JOIN products p ON p.id = c.product_id
             WHERE c.user_id = ?'
        );
        $stmt->execute([$userId]);
        return (float) $stmt->fetchColumn();
    }
}