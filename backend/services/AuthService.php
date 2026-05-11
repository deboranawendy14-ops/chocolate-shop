<?php
class AuthService {
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public static function generateToken(array $payload): string {
        $header  = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + (60 * 60 * 24); // 24 horas
        $payload = base64_encode(json_encode($payload));
        $signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
        return "$header.$payload.$signature";
    }

    public static function generateResetToken(): string {
        return bin2hex(random_bytes(32));
    }
}