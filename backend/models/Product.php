<?php
class Product {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(array $filters = []): array {
        $sql    = 'SELECT p.*, s.quantity as stock FROM products p LEFT JOIN stock s ON s.product_id = p.id WHERE p.active = 1';
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= ' AND p.category = ?';
            $params[] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $sql .= ' AND p.name LIKE ?';
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= ' ORDER BY p.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            'SELECT p.*, s.quantity as stock FROM products p LEFT JOIN stock s ON s.product_id = p.id WHERE p.id = ? AND p.active = 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            'INSERT INTO products (name, description, price, category, image_url) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image_url']
        ]);
        $productId = (int) $this->db->lastInsertId();

        // Criar stock inicial
        $stmt = $this->db->prepare('INSERT INTO stock (product_id, quantity) VALUES (?, ?)');
        $stmt->execute([$productId, $data['stock'] ?? 0]);

        return $productId;
    }

    public function update(int $id, array $data): void {
        $stmt = $this->db->prepare(
            'UPDATE products SET name = ?, description = ?, price = ?, category = ?, image_url = ? WHERE id = ?'
        );
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image_url'],
            $id
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('UPDATE products SET active = 0 WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function getCategories(): array {
        $stmt = $this->db->query('SELECT DISTINCT category FROM products WHERE active = 1 ORDER BY category');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}