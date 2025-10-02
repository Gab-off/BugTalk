<?php require_once __DIR__ . '/../layouts/head.php'; ?>

    <body class="admin-page-bg">
    <main class="container post-view-container">
        <?php require_once __DIR__ . '/../layouts/header.php'; ?>
        <a href="/" class="link-voltar"><< Voltar para o Fórum</a>

        <div class="post-card-full">
            <p class="post-author"><?= htmlspecialchars($post['autor']) ?></p>
            <h1 class="post-title-full"><?= htmlspecialchars($post['titulo']) ?></h1>
            <div class="post-content-full">
                <?= nl2br(htmlspecialchars($post['conteudo'])) ?>
            </div>
        </div>

        <div class="comentarios-section">
            <h2 class="comentarios-title">Comentários</h2>

            <?php if (isset($_SESSION['id_usuario'])): ?>
                <form action="/comentario/criar" method="POST" class="comment-form">
                    <input type="hidden" name="id_pai" value="<?= htmlspecialchars($id_post) ?>">
                    <input type="hidden" name="tipo_pai" value="POST">
                    <input type="hidden" name="id_post" value="<?= htmlspecialchars($id_post) ?>">
                    <textarea name="texto" rows="4" required placeholder="Deixe seu comentário..."></textarea>
                    <button type="submit" class="btn">Comentar</button>
                </form>
            <?php else: ?>
                <p class="text-center p-4"><a href="/login" class="link-acao">Faça login</a> para deixar um comentário.
                </p>
            <?php endif; ?>

            <div class="comentarios-list">
                <?php
                if (!function_exists('renderizarComentarios')) {
                    function renderizarComentarios($comentarios, $id_post)
                    {
                        echo '<ul class="comentarios-aninhados">';
                        foreach ($comentarios as $comentario) {
                            echo '<li>';
                            echo '<div class="comentario-card">';
                            echo '<p class="comentario-autor">' . htmlspecialchars($comentario['autor']) . '</p>';
                            echo '<p class="comentario-texto">' . nl2br(htmlspecialchars($comentario['texto'])) . '</p>';

                            if (isset($_SESSION['id_usuario'])) {
                                echo '<form action="/comentario/criar" method="POST" class="comment-form-reply">';
                                echo '<input type="hidden" name="id_pai" value="' . $comentario['id'] . '">';
                                echo '<input type="hidden" name="tipo_pai" value="Comentario">';
                                echo '<input type="hidden" name="id_post" value="' . htmlspecialchars($id_post) . '">';
                                echo '<textarea name="texto" rows="2" required placeholder="Responder..."></textarea>';
                                echo '<button type="submit" class="btn-reply">Responder</button>';
                                echo '</form>';
                            }

                            if (!empty($comentario['respostas'])) {
                                renderizarComentarios($comentario['respostas'], $id_post);
                            }
                            echo '</div>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                }

                if (empty($comentarios)) {
                    echo '<p>Nenhum comentário ainda.</p>';
                } else {
                    renderizarComentarios($comentarios, $id_post);
                }
                ?>
            </div>
        </div>
    </main>
    </body>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>