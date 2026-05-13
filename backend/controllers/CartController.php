<?php
class CartController {
    private Cart $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function index(): void {
        $payload = AuthMiddleware::verify();
        $items   = $this->cart->findByUser($payload['id']);
        $total   = $this->cart->getTotal($payload['id']);

        Response::success([
            'items' => $items,
            'total' => $total
        ]);
    }

    public function store(): void {
        $payload = AuthMiddleware::verify();
        $data    = json_decode(file_get_contents('php://input'), true);

        if (empty($data['product_id'])) {
            Response::error('Produto é obrigatório.');
            return;
        }

        $this->cart->addItem(
            $payload['id'],
            (int) $data['product_id'],
            (int) ($data['quantity'] ?? 1)
        );

        Response::success([], 'Produto adicionado ao carrinho!');
    }

    public function update(string $id): void {
        $payload = AuthMiddleware::verify();
        $data    = json_decode(file_get_contents('php://input'), true);

        if (empty($data['quantity']) || $data['quantity'] < 1) {
            Response::error('Quantidade inválida.');
            return;
        }

        $this->cart->updateItem((int) $id, $payload['id'], (int) $data['quantity']);
        Response::success([], 'Carrinho actualizado!');
    }

    public function destroy(string $id): void {
        $payload = AuthMiddleware::verify();
        $this->cart->removeItem((int) $id, $payload['id']);
        Response::success([], 'Item removido do carrinho!');
    }
}