<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    public function generateToken(UserModel $data): string
    {
        $secret_key = $_ENV['SECRET_KEY'];
        $user_id = $data->id;
        $user_email = $data->email;

        $payload = [
            "sub" => $user_id,
            "email" => $user_email,
            "iat" => time(),
            "exp" => time() + 3600
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return $jwt;
    }

    public function validateToken(string $token): object
    {
        $secret_key = $_ENV['SECRET_KEY'];

        try {
            $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

            $revoked_token_repository = new RevokedTokenRepository();
            $already_revoked = $revoked_token_repository->find($token);

            if ($already_revoked)   throw new Exception('Token revoked');

            return $decoded;
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid token: ' . $e->getMessage());
        }
    }

    public function invalidateToken(string $token): void
    {
        $revoked_token = new RevokedTokenModel(['token' => $token]);
        $revoked_token_repository = new RevokedTokenRepository();

        $already_revoked = $revoked_token_repository->find($token);

        if ($already_revoked) throw new Exception('Token already revoked');

        $revoked_token_repository->create($revoked_token);
    }
}
