<?php
class AuthMiddleware implements IMiddleware
{
    public function execute(array $headers, array $query, array $body): void
    {
        try {
            $token = $headers['Authorization'] ?? null;

            if (!$token) {
                echo json_encode(['error' => 'Token is required']);
                http_response_code(401);
                exit;
            }

            $auth_service = new AuthService();
            $auth_service->validateToken($token);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            http_response_code(401);
            exit;
        }
    }
}
