<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\UsuarioController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\AdminController;
use App\Controllers\ComentarioController;


$authController = new AuthController();
$usuarioController = new UsuarioController;
$postController = new PostController;
$homeController = new HomeController;
$adminController = new AdminController();
$comentarioController = new ComentarioController();


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

    case '/post/vote':
        if ($requestMethod === 'POST') {
            $postController->vote();
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

    case '/admin/usuarios':
        if ($requestMethod === 'GET') {
            $adminController->showUsuarios();
        }
        break;

    // Esta rota espera um ID na URL, como: /admin/usuario/posts?id=123
    case '/admin/usuario/posts':
        if ($requestMethod === 'GET') {
            $adminController->showUsuarioPosts();
        }
        break;

    // Rota para ver um post específico (ex: /post/ver?id=123)
    case '/post/ver':
        if ($requestMethod === 'GET') {
            $postController->show();
        }
        break;

    // Rota para salvar um novo comentário
    case '/comentario/criar':
        if ($requestMethod === 'POST') {
            $comentarioController->criar();
        }
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}