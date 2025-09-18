<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Tag;

class HomeController
{
    public function index()
    {
        $postModel = new Post();
        $tagModel = new Tag();

        $id_usuario_logado = $_SESSION['id_usuario'] ?? null;

        $posts = $postModel->findAll($id_usuario_logado);
        $tags = $tagModel->findAll();

        require_once __DIR__ . '/../Views/home.php';
    }
}
