<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController {
    public function showLoginForm() {
        if (isset($_SESSION['usuario_id'])) {
            header('Location: /');
            exit();
        }
        require_once '../src/Views/auth/login.php';
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        $usuarioModel = new Usuario();
        $record = $usuarioModel->findByEmail($email);

        if ($record && password_verify($senha, $record->get('senha'))) {
            $_SESSION['usuario_id'] = $record->get('id');
            $_SESSION['usuario_nome'] = $record->get('nome');
            $_SESSION['usuario_isAdmin'] = $record->get('isAdmin') ?? false;

            header('Location: /');
            exit();
        } else {
            $erro = 'Email ou senha inválidos.';
            require_once '../src/Views/auth/login.php';
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }
}