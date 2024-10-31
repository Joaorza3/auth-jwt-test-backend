<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include '../vendor/autoload.php';

include '../src/database/Connection.php';

foreach (glob('../src/interfaces/*.php') as $file) include $file;

include '../src/classes/BaseRoute.php';

foreach (glob('../src/routes/*.php') as $file) include $file;
foreach (glob('../src/middlewares/*.php') as $file) include $file;
foreach (glob('../src/models/*.php') as $file) include $file;
foreach (glob('../src/repositories/*.php') as $file) include $file;
foreach (glob('../src/services/*.php') as $file) include $file;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = $_GET['path'] ?? '';

$router = new Router();

$auth_middleware = new AuthMiddleware();

(new UserRouter('/users', $auth_middleware))->registerRoutes($router);
(new AuthRouter('/auth'))->registerRoutes($router);

$router->run($method, $path);
