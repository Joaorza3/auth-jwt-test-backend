<?php
class AuthRouter extends BaseRoute
{

    public function registerRoutes(IRouter $router): void
    {
        $router->addRoute($this, 'POST', '/create', function ($query_params, $body) {
            $auth_service = new AuthService();
            $user = $auth_service->createUser(new UserModel($body));

            echo json_encode(['user' => $user]);
        });

        $router->addRoute($this, 'POST', '/login', function ($query_params, $body) {
            $email = $body['email'] ?? null;
            $password = $body['password'] ?? null;

            if (!$email || !$password) {
                echo json_encode(['error' => 'Email and password are required']);
                return;
            }

            $auth_service = new AuthService();
            $token = $auth_service->login($email, $password);

            echo json_encode(['token' => $token]);
        });

        $router->addRoute($this, 'GET', '/validate', function ($query_params, $body) {
            $token = getallheaders()['Authorization'] ?? null;

            $auth_service = new AuthService();
            $data = $auth_service->validateToken($token);

            echo json_encode(['data' => $data]);
        });

        $router->addRoute($this, 'GET', '/logout', function ($query_params, $body) {
            $token = getallheaders()['Authorization'] ?? null;

            $auth_service = new AuthService();
            $auth_service->logout($token);

            echo json_encode(['message' => 'Logout']);
        });
    }
}
