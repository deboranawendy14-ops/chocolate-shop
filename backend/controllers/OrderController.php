<?php
class OrderController {
    private Order $order;
    private Cart $cart;
    private Stock $stock;
    private ExchangeRateService $exchangeService;

    public function __construct() {
        $this->order           = new Order();
        $this->cart            = new Cart();
        $this->stock           = new Stock();
        $this->exchangeService = new ExchangeRateService();
    }

    public function index(): void {
        $payload = AuthMiddleware::verify();

        if ($payload['role'] === 'admin') {
            $orders = $this->order->findAll();
        } else {
            $orders = $this->order->findByUser($payload['id']);
        }

        Response::success($orders);
    }

    public function show(string $id): void {
        $payload = AuthMiddleware::verify();
        $order   = $this->order->findById((int) $id);

        if (!$order) {
            Response::notFound();
            return;
        }

        if ($payload['role'] !== 'admin' && $order['user_id'] !== $payload['id']) {
            Response::error('Acesso negado.', 403);
            return;
        }

        $items         = $this->order->getItems((int) $id);
        $order['items'] = $items;

        Response::success($order);
    }

    public function store(): void {
        $payload  = AuthMiddleware::verify();
        $data     = json_decode(file_get_contents('php://input'), true);
        $currency = $data['currency'] ?? 'USD';
        $items    = $this->cart->findByUser($payload['id']);

        if (empty($items)) {
            Response::error('O carrinho está vazio.');
            return;
        }

        $total     = $this->cart->getTotal($payload['id']);
        $rate      = $this->exchangeService->getRate('USD', $currency);
        $totalLocal = round($total * $rate, 2);

        $orderId = $this->order->create($payload['id'], $total, $currency, $totalLocal);

        foreach ($items as $item) {
            $this->order->addItem($orderId, $item['product_id'], $item['quantity'], $item['price']);
            $this->stock->decrease($item['product_id'], $item['quantity']);
        }

        $this->cart->clearCart($payload['id']);

        Response::success(['order_id' => $orderId], 'Pedido realizado com sucesso!');
    }

    public function updateStatus(string $id): void {
        AuthMiddleware::verifyAdmin();
        $data = json_decode(file_get_contents('php://input'), true);

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        if (empty($data['status']) || !in_array($data['status'], $validStatuses)) {
            Response::error('Estado inválido.');
            return;
        }

        $this->order->updateStatus((int) $id, $data['status']);
        Response::success([], 'Estado actualizado com sucesso!');
    }
}