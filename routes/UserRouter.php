<?php
class UserRouter extends BaseRoute
{
    public function registerRoutes(IRouter $router): void
    {
        $router->addRoute($this, 'GET', '/', function ($query_params, $body) {

            $user_repository = new UserRepository();
            $users = $user_repository->findAll();

            echo json_encode(['users' => $users]);
        });

        $router->addRoute($this, 'POST', '/', function ($query_params, $body) {

            $user_repository = new UserRepository();
            $user = $user_repository->create($body);

            echo json_encode(['user' => $user]);
        });
    }
}
