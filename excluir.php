<?php
session_start();

// O "GUARDA" DA PÁGINA
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require 'conexao.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = 'MATCH (n:Usuario) WHERE id(n) = $id DETACH DELETE n';
    try {
        $client->run($query, ['id' => $id]);
    } catch (\Exception $e) {
        // Poderia gravar um log de erro aqui
    }
}

// Redireciona de volta para a lista de qualquer forma
header("Location: admin.php");
exit();