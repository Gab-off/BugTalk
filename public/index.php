<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UsuarioController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$usuarioController = new UsuarioController;

switch ($uri) {
    case '/cadastro':
        if ($requestMethod == 'GET') {
            $usuarioController->showCadastroForm();
        } elseif ($requestMethod == 'POST') {
            $usuarioController->cadastrar();
        }
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}