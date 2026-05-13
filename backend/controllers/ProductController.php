<?php
class ProductController {
    private Product $product;
    private ExchangeRateService $exchangeService;

    public function __construct() {
        $this->product         = new Product();
        $this->exchangeService = new ExchangeRateService();
    }

    public function index(): void {
        $filters   = [
            'category' => $_GET['category'] ?? null,
            'search'   => $_GET['search']   ?? null,
        ];
        $currency  = $_GET['currency'] ?? 'USD';
        $products  = $this->product->findAll($filters);
        $rate      = $this->exchangeService->getRate('USD', $currency);

        foreach ($products as &$p) {
            $p['price_converted'] = round($p['price'] * $rate, 2);
            $p['currency']        = $currency;
        }

        Response::success($products);
    }

    public function show(string $id): void {
        $product = $this->product->findById((int) $id);

        if (!$product) {
            Response::notFound();
            return;
        }

        Response::success($product);
    }

    public function store(): void {
        AuthMiddleware::verifyAdmin();
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name']) || empty($data['price']) || empty($data['category'])) {
            Response::error('Nome, preço e categoria são obrigatórios.');
            return;
        }

        $id = $this->product->create($data);
        Response::success(['id' => $id], 'Produto criado com sucesso!');
    }

    public function update(string $id): void {
        AuthMiddleware::verifyAdmin();
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$this->product->findById((int) $id)) {
            Response::notFound();
            return;
        }

        $this->product->update((int) $id, $data);
        Response::success([], 'Produto actualizado com sucesso!');
    }

    public function destroy(string $id): void {
        AuthMiddleware::verifyAdmin();

        if (!$this->product->findById((int) $id)) {
            Response::notFound();
            return;
        }

        $this->product->delete((int) $id);
        Response::success([], 'Produto removido com sucesso!');
    }

    public function categories(): void {
        $categories = $this->product->getCategories();
        Response::success($categories);
    }
}