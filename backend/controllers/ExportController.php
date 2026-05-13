<?php
class ExportController {
    private Order $order;
    private Stock $stock;

    public function __construct() {
        $this->order = new Order();
        $this->stock = new Stock();
    }

    public function exportOrdersCsv(): void {
        $payload = AuthMiddleware::verify();

        if ($payload['role'] === 'admin') {
            $orders = $this->order->findAll();
        } else {
            $orders = $this->order->findByUser($payload['id']);
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="pedidos_' . date('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

        fputcsv($out, ['ID', 'Cliente', 'Email', 'Total USD', 'Moeda', 'Total Local', 'Estado', 'Data']);

        foreach ($orders as $order) {
            fputcsv($out, [
                $order['id'],
                $order['user_name']  ?? 'N/A',
                $order['user_email'] ?? 'N/A',
                $order['total_usd'],
                $order['currency'],
                $order['total_local'],
                $order['status'],
                $order['created_at']
            ]);
        }

        fclose($out);
        exit();
    }

    public function exportStockCsv(): void {
        AuthMiddleware::verifyAdmin();
        $stock = $this->stock->findAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="stock_' . date('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, ['ID', 'Produto', 'Categoria', 'Preço', 'Quantidade', 'Alerta Mínimo', 'Actualizado em']);

        foreach ($stock as $item) {
            fputcsv($out, [
                $item['product_id'],
                $item['name'],
                $item['category'],
                $item['price'],
                $item['quantity'],
                $item['min_alert'],
                $item['updated_at']
            ]);
        }

        fclose($out);
        exit();
    }
}