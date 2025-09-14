<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: admin.php');
    exit();
}

require 'conexao.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $query = 'CREATE (n:Usuario {nome: $nome, email: $email, senha: $senha, data_cadastro: datetime(), admin: false})';
        try {
            $client->run($query, ['nome' => $nome, 'email' => $email, 'senha' => $senhaHash]);
            header('Location: login.php');
            exit();
        } catch (\Exception $e) {
            $erro = "ERRO REAL DO BANCO: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar - Fórum TCC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #127a8c; }</style>
</head>
<body class="flex items-center justify-center min-h-screen">

<div class="bg-transparent text-white w-full max-w-md p-8">
    <h1 class="text-4xl font-extrabold text-center mb-8">CRIAR CONTA</h1>
    <?php if ($erro): ?>
        <div class="bg-red-500/50 text-white p-3 rounded mb-4 text-center"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <form action="cadastrar.php" method="POST">
        <div class="mb-4">
            <input type="text" name="nome" placeholder="Nome completo" required class="w-full p-3 rounded bg-white/10 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-white">
        </div>
        <div class="mb-4">
            <input type="email" name="email" placeholder="E-mail" required class="w-full p-3 rounded bg-white/10 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-white">
        </div>
        <div class="mb-6">
            <input type="password" name="senha" placeholder="Senha" required class="w-full p-3 rounded bg-white/10 border border-white/20 placeholder-gray-300 focus:outline-none focus:border-white">
        </div>
        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 font-bold py-3 px-4 rounded transition-colors">CADASTRAR</button>
    </form>
    <p class="text-center mt-6">
        Já tem uma conta? <a href="login.php" class="font-bold hover:underline">Entre aqui</a>
    </p>
</div>

</body>
</html>


