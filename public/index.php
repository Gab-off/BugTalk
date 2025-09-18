<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\UsuarioController;
use App\Controllers\HomeController;
use App\Controllers\PostController;

$authController = new AuthController();
$usuarioController = new UsuarioController;
$postController = new PostController;
$homeController = new HomeController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($uri) {

    case '/':
        $homeController->index();
        break;

    case '/post/criar':
        if ($requestMethod === 'GET') {
            $postController->showCriarForm();
        } elseif ($requestMethod === 'POST') {
            $postController->criar();
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