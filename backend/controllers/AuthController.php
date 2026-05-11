<?php
class AuthController {
    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            Response::error('Preencha todos os campos.');
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Response::error('Email inválido.');
            return;
        }

        if (strlen($data['password']) < 6) {
            Response::error('A password deve ter pelo menos 6 caracteres.');
            return;
        }

        if ($this->user->emailExists($data['email'])) {
            Response::error('Este email já está registado.');
            return;
        }

        $id = $this->user->create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password_hash' => AuthService::hashPassword($data['password']),
            'role'          => 'customer'
        ]);

        $user  = $this->user->findById($id);
        $token = AuthService::generateToken([
            'id'    => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'name'  => $user['name']
        ]);

        Response::success(['token' => $token, 'user' => $user], 'Conta criada com sucesso!');
    }

    public function login(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            Response::error('Email e password são obrigatórios.');
            return;
        }

        $user = $this->user->findByEmail($data['email']);

        if (!$user || !AuthService::verifyPassword($data['password'], $user['password_hash'])) {
            Response::error('Email ou password incorrectos.', 401);
            return;
        }

        $token = AuthService::generateToken([
            'id'    => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'name'  => $user['name']
        ]);

        unset($user['password_hash']);
        Response::success(['token' => $token, 'user' => $user], 'Login realizado com sucesso!');
    }

    public function forgotPassword(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email'])) {
            Response::error('Email é obrigatório.');
            return;
        }

        $user = $this->user->findByEmail($data['email']);

        if (!$user) {
            Response::success([], 'Se o email existir, receberá instruções.');
            return;
        }

        $token = AuthService::generateResetToken();
        $this->user->updateResetToken($data['email'], $token);

        Response::success(['reset_token' => $token], 'Token de recuperação gerado.');
    }

    public function resetPassword(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['token']) || empty($data['password'])) {
            Response::error('Token e password são obrigatórios.');
            return;
        }

        $user = $this->user->findByResetToken($data['token']);

        if (!$user) {
            Response::error('Token inválido ou expirado.');
            return;
        }

        $this->user->updatePassword($user['id'], AuthService::hashPassword($data['password']));
        Response::success([], 'Password alterada com sucesso!');
    }

    public function me(): void {
        $payload = AuthMiddleware::verify();
        $user    = $this->user->findById($payload['id']);

        if (!$user) {
            Response::notFound();
            return;
        }

        Response::success($user);
    }
}