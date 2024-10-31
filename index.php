<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include 'vendor/autoload.php';

include 'database/Connection.php';

foreach (glob('interfaces/*.php') as $file) include $file;

include 'classes/BaseRoute.php';

foreach (glob('routes/*.php') as $file) include $file;
foreach (glob('middlewares/*.php') as $file) include $file;
foreach (glob('models/*.php') as $file) include $file;
foreach (glob('repositories/*.php') as $file) include $file;
foreach (glob('services/*.php') as $file) include $file;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// echo '<pre>';
// print_r($_ENV);
// echo '</pre>';
// echo die();


// // Captura o método HTTP e a rota a partir da URL
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = $_GET['path'] ?? '';

// echo '<pre>';
// print_r($_GET);
// echo '</pre>';
// echo die();

// // Cria uma instância do roteador
$router = new Router();

$auth_middleware = new AuthMiddleware();

(new UserRouter('/users', $auth_middleware))->registerRoutes($router);
(new AuthRouter('/auth'))->registerRoutes($router);

// // Executa o roteador
$router->run($method, $path);
