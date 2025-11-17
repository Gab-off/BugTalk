<?php
session_start();
require_once __DIR__ . '/../src/Models/Post.php';

use App\Post;

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'error' => 'not_logged_in']);
    exit();
}

$id_post = (int)($_POST['post_id'] ?? 0);
$id_usuario = $_SESSION['id_usuario'];

if ($id_post > 0) {
    $postModel = new \App\Models\Post();
    $postModel->toggleVote($id_post, $id_usuario);

    $result = $postModel->findById($id_post, $id_usuario);
    $upvotes = $result['upvotes'] ?? 0;
    echo json_encode(['success' => true, 'novo_total' => $upvotes]);
}
echo json_encode(['success' => false, 'error' => 'invalid_post']);
exit();