<?php

namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController
{
    /**
     * Apenas exibe a página com o formulário de cadastro.
     */
    public function showCadastroForm()
    {
        require_once '../src/Views/usuarios/cadastrar.php';
    }

    public function cadastrar()
    {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if (empty($nome) || empty($email) || empty($senha)) {
            $erro = "Todos os campos são obrigatórios.";
            require_once '../src/Views/usuarios/cadastrar.php';
            return;
        }

        $usuarioModel = new Usuario();

        if ($usuarioModel->findByEmail($email)) {
            $erro = "Este e-mail já está em uso.";
            require_once '../src/Views/usuarios/cadastrar.php';
            return;
        }

        $sucesso = $usuarioModel->create([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha
        ]);

        if ($sucesso) {
            header('Location: /login');
            exit();
        } else {
            $erro = "Ocorreu um erro ao criar sua conta. Tente novamente.";
            require_once '../src/Views/usuarios/cadastrar.php';
        }
    }
}