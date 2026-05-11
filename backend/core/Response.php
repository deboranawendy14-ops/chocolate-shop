<?php
class Response {
    public static function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public static function success(mixed $data, string $message = 'Sucesso'): void {
        self::json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ]);
    }

    public static function error(string $message, int $status = 400): void {
        self::json([
            'success' => false,
            'error'   => $message
        ], $status);
    }

    public static function unauthorized(): void {
        self::error('Não autorizado.', 401);
    }

    public static function notFound(): void {
        self::error('Recurso não encontrado.', 404);
    }
}