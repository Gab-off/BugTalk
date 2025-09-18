<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<body class="container criar_bg">
<main>
    <h1>Criar Post</h1>
    <!--        Erro ao se criar um post-->
    <?php if (isset($erro)): ?>
        <div><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <form action="/post/criar" method="post">
        <div>
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>
        </div>
        <div>
            <label for="conteudo">Conteúdo:</label>
            <textarea name="conteudo" id="conteudo" cols="30" rows="10" required></textarea>
        </div>
        <button type="submit">Criar</button>
        <a href="/">Cancelar</a>
    </form>
</main>
</body>
