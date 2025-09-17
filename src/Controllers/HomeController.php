<?php
namespace App\Controllers;

use App\Models\Post;

class HomeController {
    public function index() {
        $postModel = new Post();
        $posts = $postModel->findAll();
        require_once __DIR__ . '/../Views/home.php';
    }
}
