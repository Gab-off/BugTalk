<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<body class="admin-page-bg">
<main class="container admin-container">
    <a href="/admin/usuarios" class="link-voltar"><< Voltar para a lista de usuários</a>

    <h1 class="admin-title">
        Posts de: <?= isset($usuario['nome']) ? htmlspecialchars($usuario['nome']) : 'Usuário Desconhecido' ?>
    </h1>

    <div class="posts-container-admin">
        <?php if (empty($posts)): ?>
            <div class="card-vazio">
                <p>Este usuário ainda não criou nenhum post.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card-admin">
                    <h2><?= htmlspecialchars($post['titulo']) ?></h2>
                    <p><?= nl2br(htmlspecialchars(substr($post['conteudo'], 0, 300))) . '...' ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
