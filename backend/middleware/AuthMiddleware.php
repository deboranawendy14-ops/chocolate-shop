<?php
class AuthMiddleware {
    public static function verify(): array {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token  = str_replace('Bearer ', '', $header);

        if (!$token) {
            Response::unauthorized();
        }

        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) Response::unauthorized();

            $payload = json_decode(base64_decode($parts[1]), true);

            if (!$payload || $payload['exp'] < time()) {
                Response::error('Token expirado.', 401);
            }

            return $payload;
        } catch (Exception $e) {
            Response::unauthorized();
        }

        return [];
    }

    public static function verifyAdmin(): array {
        $payload = self::verify();
        if ($payload['role'] !== 'admin') {
            Response::error('Acesso negado. Apenas administradores.', 403);
        }
        return $payload;
    }
}