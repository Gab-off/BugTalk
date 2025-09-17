<?php

namespace App\Controllers;

use App\Models\Post;

class PostController
{

    public function showCriarForm()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit();
        }
        require_once __DIR__ . '/src/Views/posts/criar.php';
    }

    public function criar()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit();
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $conteudo = trim($_POST['conteudo'] ?? '');

        if (empty($titulo) || ($conteudo)) {
            $erro = 'Título e conteúdo são obrigatórios.';
            require_once __DIR__ . '/src/Views/posts/criar.php';
            return;
        }
        $postModel = new Post();
        $sucesso = $postModel->create([
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'usuario_id' => $_SESSION['usuario_id']
        ]);

        if ($sucesso) {
            header('Location: /');
            exit();
        } else {
            $erro = 'Ocorreu um erro ao criar seu post. Tente novamente.';
            require_once __DIR__ . '/src/Views/posts/criar.php';
        }
    }
}