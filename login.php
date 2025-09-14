<?php
session_start();

if(isset($_SESSION['usuario_id'])) {
    header('Location: admin.php');
    exit();
}

require 'conexao.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos!";
    } else {
        $query = 'MATCH (n:Usuario) WHERE n.email = $email RETURN n.senha AS senha, id(n) AS id, n.nome AS nome, n.admin AS isAdmin LIMIT 1';;
        /** @var \Laudis\Neo4j\Databags\Result $result */
        $result = $client->run($query, ['email' => $email]);
        $record = $result->first();

        if($record && password_verify($senha, $record->get('senha'))) {
            $_SESSION['usuario_id'] = $record->get('id');
            $_SESSION['usuario_nome'] = $record->get('nome');
            $_SESSION['usuario_isAdmin'] = $record->get('isAdmin') ?? false;

            if ($_SESSION['usuario_isAdmin'] === true) {
                // Se for admin, vai para a área de admin
                header('Location: index.php');
            } else {
                // Se for usuário comum, vai para o dashboard
                header('Location: index.php');
            }
            exit();
        } else {
            $erro = "Email ou senha inválidos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar - Fórum TCC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilo para simular o fundo da sua imagem */
        body {
            background: linear-gradient(#007286, #002D35);
            font-family: 'VCR OSD Mono', monospace;
        }

        input {
            font-family: 'ProFont IIx Nerd Font', monospace;
        }

        .text-terms {
            font-family: 'ProFont IIx Nerd Font', monospace;

        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

<div class="bg-transparent text-white w-full max-w-lg p-8">
    <h1 class="text-4xl  text-center mb-2">ENTRAR</h1>
    <p class="text-terms text-center text-sm text-gray-200 mb-8">
        Ao clicar em cadastrar você concorda com nossos <br>
        <a href="#" class="text-cyan-400 underline">Termos de serviço</a> e com a nossa <a href="#" class="text-cyan-400 underline">política de privacidade</a>.
    </p>

    <?php if ($erro): ?>
        <div class="bg-red-500/50 text-white p-3 rounded mb-4 text-center"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-4">
            <input type="email" name="email" placeholder="E-mail / Usuário" required class="w-full p-3 rounded bg-cyan-800 transition shadow-sm placeholder-cyan-200 invalid:bg-red focus:outline-none focus:scale-105 focus:shadow-lg focus:bg-cyan-200 focus:text-cyan-800">
        </div>
        <div class="mb-6">
            <input type="password" name="senha" placeholder="Senha" required class="w-full p-3 rounded bg-cyan-800 transition shadow-sm placeholder-cyan-200 invalid:bg-red focus:outline-none focus:scale-105 focus:shadow-lg focus:bg-cyan-200 focus:text-cyan-800">
            <a href="#" class="text-xs text-gray-300 hover:underline float-right my-2">ESQUECEU A SENHA?</a>
        </div>
        <button type="submit" class="w-full bg-cyan-800 hover:bg-cyan-700 transition duration-300 ease-in-out font-bold py-3 px-4 hover:scale-105 rounded">ENTRAR</button>
    </form>
    <p class="text-center mt-6">
        Não tem uma conta? <a href="cadastrar.php" class="text-cyan-300 font-bold hover:underline">Cadastre-se</a>
    </p>
</div>

</body>
</html>
