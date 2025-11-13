<?php

namespace App\Controllers;

use App\Models\Usuario;

class AuthController
{
    public function showLoginForm()
    {
        if (isset($_SESSION['id_usuario'])) {
            header('Location: /');
            exit();
        }
        require_once __DIR__ .  '/../Views/auth/login.php';
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        $usuarioModel = new Usuario();
        $record = $usuarioModel->findByEmail($email);

        if ($record) {
            // 1. Pegamos o objeto Node
            $userNode = $record->get('n');

            // 2. Verificamos se a conta foi banida (usando try-catch para tratar propriedades que não existem)
            try {
                if ($userNode->getProperty('banned') === true) {
                    $erro = 'Esta conta foi permanentemente banida.';
                    require_once __DIR__ . '/../Views/auth/login.php';
                    return;
                }
            } catch (\Exception $e) {
                // Propriedade não existe, continuar normalmente
            }

            // 3. Verificamos se a conta está em timeout
            try {
                $timeoutDate = $userNode->getProperty('timeoutUntil');
                if ($timeoutDate !== null) {
                    // A biblioteca já converte a data para um objeto DateTime do PHP
                    if ($timeoutDate > new \DateTime('now', $timeoutDate->getTimezone())) {
                        $erro = 'Sua conta está suspensa temporariamente até ' . $timeoutDate->format('d/m/Y H:i');
                        require_once __DIR__ . '/../Views/auth/login.php';
                        return;
                    }
                }
            } catch (\Exception $e) {
                // Propriedade não existe, continuar normalmente
            }
        }

        if ($record && password_verify($senha, $record->get('senha'))) {
            $_SESSION['id_usuario'] = $record->get('id');
            $_SESSION['usuario_nome'] = $record->get('nome');
            $_SESSION['usuario_isAdmin'] = (bool)$record->get('isAdmin');

            header('Location: /');
            exit();
        } else {
            $erro = 'Email ou senha inválidos.';
            require_once __DIR__ .  '/../Views/auth/login.php';
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit();
    }
}