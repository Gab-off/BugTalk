<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\UsuarioController;

$authController = new AuthController();
$usuarioController = new UsuarioController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($uri) {

    case '/':
        if ($requestMethod == 'GET') {
            require_once __DIR__ . '/../src/Views/semLogin.php';
        } elseif ($requestMethod == 'POST') {
            require_once __DIR__ . '/../src/Views/comLogin.php';
        }
        break;

    case '/cadastro':
        if ($requestMethod == 'GET') {
            $usuarioController->showCadastroForm();
        } elseif ($requestMethod == 'POST') {
            $usuarioController->cadastrar();
        }
        break;

    case '/login':
        if ($requestMethod == 'GET') {
            $authController->showLoginForm();
        } elseif ($requestMethod == 'POST') {
            $authController->login();
        }
        break;

    case '/logout':
        $authController->logout();
        break;



    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}