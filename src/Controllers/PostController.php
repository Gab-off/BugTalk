<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Core\AuthGuard;

class PostController
{
    use AuthGuard;

    public function showCriarForm()
    {
        $this->checkAuth();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }
        require_once __DIR__ . '/../Views/posts/criar.php';
    }

    public function criar()
    {
        $this->checkAuth();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = trim($_POST['titulo'] ?? '');
            $conteudo = trim($_POST['conteudo'] ?? '');
            $tagsInput = trim($_POST['tags'] ?? '');
            $id_usuario = $_SESSION['id_usuario'];
        }

        if (empty($titulo) || empty($conteudo)) {
            $erro = 'Título e conteúdo são obrigatórios.';
            require_once __DIR__ . '/../Views/posts/criar.php';
            return;
        }

        $postModel = new Post();
        $tagModel = new Tag();

        $id_post_criado = $postModel->create([
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'id_usuario' => $_SESSION['id_usuario']
        ]);

        if ($id_post_criado !== null) {
            //Separa as tags digitadas com vírgula
            $tagsArray = explode(',', $tagsInput);
            foreach ($tagsArray as $nomeTag) {
                $nomeTag = trim(strtolower($nomeTag));
                if (!empty($nomeTag)) {
                    $id_tag = $tagModel->findOrCreateByName($nomeTag);
                    $postModel->associarTag($id_post_criado, $id_tag);
                }
            }
            header('Location: /');
            exit();
        } else {
            $erro = 'Ocorreu um erro ao criar seu post. Tente novamente.';
            require_once __DIR__ . '/../Views/posts/criar.php';
        }
    }

    public function vote() {
        $this->checkAuth();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /login');
            exit();
        }

        $id_post = (int) ($_POST['post_id'] ?? 0);
        $id_usuario = $_SESSION['id_usuario'];

        if ($id_post > 0) {
            $postModel = new Post();
            $postModel->toggleVote($id_post, $id_usuario);
        }

        header('Location: '. $_SERVER['HTTP_REFERER'] ?? '/' );;
        exit();
    }

    // Dentro da classe App\Controllers\PostController

// Você já tem um método findById no seu Post.php, vamos usá-lo.
// Se não tiver, crie um método simples que busca um post pelo ID.
    /**
     * Exibe um único post e seus comentários.
     */
    public function show() {
        $id_post = (int)($_GET['id'] ?? 0);

        $postModel = new Post();
        $post = $postModel->findById($id_post); // Supondo que findById exista

        if (!$post) {
            http_response_code(404);
            echo "Post não encontrado.";
            exit();
        }

        $comentarioModel = new \App\Models\Comentario();
        $comentarios = $comentarioModel->findComentariosAninhados($id_post);

        require_once __DIR__ . '/../Views/posts/ver.php';
    }
}