<?php
class StockController {
    private Stock $stock;

    public function __construct() {
        $this->stock = new Stock();
    }

    public function index(): void {
        AuthMiddleware::verifyAdmin();
        $stock = $this->stock->findAll();
        Response::success($stock);
    }

    public function update(string $productId): void {
        AuthMiddleware::verifyAdmin();
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['quantity'])) {
            Response::error('Quantidade é obrigatória.');
            return;
        }

        $this->stock->update(
            (int) $productId,
            (int) $data['quantity'],
            (int) ($data['min_alert'] ?? 5)
        );

        Response::success([], 'Stock actualizado com sucesso!');
    }

    public function lowStock(): void {
        AuthMiddleware::verifyAdmin();
        $items = $this->stock->getLowStock();
        Response::success($items);
    }
}