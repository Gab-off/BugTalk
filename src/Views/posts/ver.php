<?php
require_once __DIR__ . '/../layouts/head-visualizar.php';
?>

<style>
    :root {
        --bg-gradient-start: #E0F7FA;
        --bg-gradient-end: #80DEEA;
        --card-bg: #FFFFFF;
        --card-hover: #F0FDFF;
        --text-primary: #006064;
        --text-secondary: #00838F;
        --text-muted: #00ACC1;
        --border-color: #26C6DA;
        --border-light: #B2EBF2;
        --input-bg: #F1F9FA;
        --code-bg: #E0F7FA;
        --button-gradient-start: #26C6DA;
        --button-gradient-end: #00ACC1;
        --tag-bg: #E0F7FA;
        --tag-hover: #B2EBF2;
        --comment-bg: #FAFEFF;
        --avatar-bg: #26C6DA;
    }

    [data-theme="dark"] {
        --bg-gradient-start: #01090B;
        --bg-gradient-end: #003842;
        --card-bg: #0F172A;
        --card-hover: #1E293B;
        --text-primary: #FFFFFF;
        --text-secondary: #67E8F9;
        --text-muted: #22D3EE;
        --border-color: #22D3EE;
        --border-light: rgba(34, 211, 238, 0.2);
        --input-bg: #1E293B;
        --code-bg: #1E293B;
        --button-gradient-start: #0E7490;
        --button-gradient-end: #164E63;
        --tag-bg: rgba(14, 116, 144, 0.3);
        --tag-hover: rgba(22, 78, 99, 0.5);
        --comment-bg: rgba(15, 23, 42, 0.5);
        --avatar-bg: #0E7490;
    }

    body {
        background: linear-gradient(to bottom, var(--bg-gradient-start), var(--bg-gradient-end));
        transition: background 0.3s ease;
    }

    .post-card, .comment-card, .sidebar-card, .form-card {
        background: var(--card-bg);
        border-color: var(--border-color);
        transition: all 0.3s ease;
    }

    .post-card:hover, .comment-card:hover {
        background: var(--card-hover);
    }

    .text-primary {
        color: var(--text-primary);
    }

    .text-secondary {
        color: var(--text-secondary);
    }

    .text-muted {
        color: var(--text-muted);
        opacity: 0.7;
    }

    .form-input {
        background: var(--input-bg);
        color: var(--text-primary);
        border-color: var(--border-color);
        transition: all 0.3s ease;
    }

    .form-input::placeholder {
        color: var(--text-muted);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--border-color);
        box-shadow: 0 0 0 3px rgba(38, 198, 218, 0.1);
    }

    .btn-primary {
        background: linear-gradient(to bottom, var(--button-gradient-start), var(--button-gradient-end));
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(38, 198, 218, 0.3);
    }

    .tag-pill {
        background: var(--tag-bg);
        border-color: var(--border-light);
        color: var(--text-secondary);
        transition: all 0.3s ease;
    }

    .tag-pill:hover {
        background: var(--tag-hover);
    }

    .action-btn {
        color: var(--text-secondary);
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        color: var(--text-primary);
        background: var(--input-bg);
    }

    .code-block {
        background: var(--code-bg);
        border-color: var(--border-light);
    }

    .code-header {
        background: var(--input-bg);
        color: var(--text-secondary);
    }

    .comment-border {
        border-color: var(--border-light);
    }

    .avatar {
        background: var(--avatar-bg);
    }
</style>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12" data-theme="light">

<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="flex gap-6 mt-6 pb-8">
    <!-- COLUNA PRINCIPAL - POST E COMENTÁRIOS -->
    <div class="flex-1 min-w-0">

        <!-- POST PRINCIPAL -->
        <article class="post-card border-2 rounded-xl p-6 mb-6 shadow-lg">
            <!-- HEADER DO POST -->
            <div class="flex gap-4 mb-4">
                <img src="/assets/img/user.svg" alt="Avatar" class="avatar w-12 h-12 rounded-full p-1.5 shadow-md">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-secondary"><?= htmlspecialchars($post['autor']) ?></span>
                        <span class="text-xs text-muted">u/<?= htmlspecialchars($post['autor']) ?></span>
                    </div>
                    <span class="text-xs text-muted">há 2 horas</span>
                </div>
            </div>

            <!-- TÍTULO -->
            <h1 class="text-3xl font-bold text-primary mb-4 leading-tight"><?= htmlspecialchars($post['titulo']) ?></h1>

            <!-- CONTEÚDO -->
            <div class="text-primary mb-6 leading-relaxed text-base">
                <?= nl2br(htmlspecialchars($post['conteudo'])) ?>
            </div>

            <!-- BLOCO DE CÓDIGO (se houver) -->
            <?php if (!empty($post['codigo'])): ?>
                <div class="code-block rounded-lg border-2 mb-6 overflow-hidden shadow-md">
                    <div class="code-header px-4 py-2 flex justify-between items-center">
                        <span class="text-sm font-bold uppercase tracking-wider"><?= htmlspecialchars($post['linguagem']) ?></span>
                    </div>
                    <pre class="p-4 overflow-x-auto text-primary"><code class="language-<?= htmlspecialchars($post['linguagem']) ?>"><?= htmlspecialchars($post['codigo']) ?></code></pre>
                </div>
            <?php endif; ?>

            <!-- IMAGEM (se houver) -->
            <?php if (!empty($post['imagem_url'])): ?>
                <img src="<?= htmlspecialchars($post['imagem_url']) ?>" alt="Post image" class="rounded-lg mb-6 max-w-full shadow-lg border-2 border-color">
            <?php endif; ?>

            <!-- TAGS -->
            <?php if (!empty($post['tags']) && count($post['tags']) > 0): ?>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php foreach ($post['tags'] as $tag): ?>
                        <span class="tag-pill px-4 py-1.5 border-2 rounded-full text-sm font-semibold cursor-pointer shadow-sm">
                            🏷️ <?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- FOOTER COM AÇÕES -->
            <div class="flex gap-3 pt-4 border-t-2 comment-border">
                <!-- UPVOTE -->
                <form action="/post/vote" method="POST" class="inline-block">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" class="action-btn flex items-center gap-2 text-sm px-4 py-2 rounded-lg font-semibold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4l-8 8h5v8h6v-8h5z"/>
                        </svg>
                        <span><?= $post['upvotes'] ?></span>
                    </button>
                </form>

                <!-- COMENTÁRIOS -->
                <a href="#comentarios" class="action-btn flex items-center gap-2 text-sm px-4 py-2 rounded-lg font-semibold">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                    Comentários
                </a>

                <!-- COMPARTILHAR -->
                <a href="#" class="action-btn flex items-center gap-2 text-sm px-4 py-2 rounded-lg font-semibold">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.52.47 1.2.77 1.96.77 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.52 9.31 6.84 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.84 0 1.52-.31 2.04-.81l7.12 4.16c-.057.21-.092.43-.092.67 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                    </svg>
                    Compartilhar
                </a>
            </div>
        </article>

        <!-- SEÇÃO DE COMENTÁRIOS -->
        <div id="comentarios" class="space-y-2">
            <h2 class="text-2xl font-bold text-secondary mb-6">💬 Comentários</h2>

            <!-- FORMULÁRIO DE COMENTÁRIO (se logado) -->
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <form action="/comentario/criar" method="POST" class="form-card mb-6 border-2 rounded-xl p-5 shadow-md">
                    <input type="hidden" name="id_pai" value="<?= htmlspecialchars($post['id']) ?>">
                    <input type="hidden" name="tipo_pai" value="Post">
                    <textarea name="texto" placeholder="Compartilhe sua opinião ou ajude a resolver o problema..." rows="3"
                              class="form-input w-full border-2 rounded-lg px-4 py-3 resize-none mb-3"></textarea>
                    <button type="submit" class="btn-primary text-white px-6 py-2.5 rounded-lg font-bold shadow-md">
                        📝 Comentar
                    </button>
                </form>
            <?php else: ?>
                <div class="form-card mb-6 border-2 rounded-xl p-5 text-center shadow-md">
                    <p class="text-secondary text-lg">
                        <a href="/login" class="font-bold underline hover:no-underline">Faça login</a> para comentar
                    </p>
                </div>
            <?php endif; ?>

            <!-- LISTA DE COMENTÁRIOS -->
            <div class="space-y-3">
                <?php if (empty($comentarios)): ?>
                    <div class="text-center py-12 text-muted">
                        <p class="text-lg">💭 Nenhum comentário ainda. Seja o primeiro!</p>
                    </div>
                <?php else: ?>
                    <?php renderizarComentarios($comentarios, $post['id']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SIDEBAR DIREITA (INFORMAÇÕES) -->
    <aside class="hidden lg:block w-80">
        <div class="sidebar-card border-2 rounded-xl p-5 sticky top-20 shadow-lg">
            <h3 class="font-bold text-secondary mb-4 text-lg">ℹ️ Informações</h3>
            <div class="space-y-4 text-sm">
                <div class="pb-3 border-b comment-border">
                    <span class="text-muted font-semibold">Criado por:</span>
                    <p class="font-bold text-secondary text-base mt-1"><?= htmlspecialchars($post['autor']) ?></p>
                </div>
                <div class="pb-3 border-b comment-border">
                    <span class="text-muted font-semibold">Linguagem:</span>
                    <p class="font-bold text-secondary text-base mt-1"><?= htmlspecialchars($post['linguagem']) ?></p>
                </div>
                <div>
                    <span class="text-muted font-semibold">Upvotes:</span>
                    <p class="font-bold text-secondary text-base mt-1">⬆️ <?= $post['upvotes'] ?></p>
                </div>
            </div>
        </div>
    </aside>
</main>

</body>

<!-- FUNÇÃO RECURSIVA PARA COMENTÁRIOS ANINHADOS -->
<?php
function renderizarComentarios($comentarios, $id_post, $nivel = 0) {
    foreach ($comentarios as $comentario):
        $padding = $nivel > 0 ? 'ml-' . min($nivel * 4, 12) : '';
        ?>
        <div class="<?= $padding ?> group">
            <!-- COMENTÁRIO CARD -->
            <div class="comment-card border-2 rounded-xl p-4 shadow-sm hover:shadow-md transition <?= $nivel > 0 ? 'mb-2' : 'mb-3' ?>">
                <!-- HEADER -->
                <div class="flex gap-3 mb-3">
                    <img src="/assets/img/user.svg" alt="Avatar" class="avatar w-9 h-9 rounded-full p-1 flex-shrink-0 shadow-sm">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-secondary text-sm"><?= htmlspecialchars($comentario['autor']) ?></span>
                            <span class="text-xs text-muted">u/<?= htmlspecialchars($comentario['autor']) ?></span>
                        </div>
                        <span class="text-xs text-muted">há 1 hora</span>
                    </div>
                </div>

                <!-- CONTEÚDO -->
                <p class="text-primary text-sm mb-3 leading-relaxed">
                    <?= nl2br(htmlspecialchars($comentario['texto'])) ?>
                </p>

                <!-- AÇÕES -->
                <div class="flex gap-3 text-xs opacity-0 group-hover:opacity-100 transition">
                    <button class="action-btn flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-semibold">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-8 8h5v8h6v-8h5z"/></svg>
                        Upvote
                    </button>
                    <button class="action-btn flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-semibold" onclick="toggleReply(this)">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        Responder
                    </button>
                </div>

                <!-- FORMULÁRIO DE RESPOSTA (HIDDEN) -->
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <div class="reply-form hidden mt-4 pt-4 border-t-2 comment-border">
                        <form action="/comentario/criar" method="POST" class="space-y-2">
                            <input type="hidden" name="id_pai" value="<?= htmlspecialchars($comentario['id']) ?>">
                            <input type="hidden" name="tipo_pai" value="Comentario">
                            <input type="hidden" name="id_post" value="<?= htmlspecialchars($id_post) ?>">
                            <textarea name="texto" placeholder="Escrever uma resposta..." rows="2"
                                      class="form-input w-full border-2 rounded-lg px-3 py-2 resize-none text-sm"></textarea>
                            <button type="submit" class="btn-primary text-white px-5 py-2 rounded-lg text-sm font-bold shadow-md">
                                💬 Responder
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RESPOSTAS ANINHADAS (RECURSÃO) -->
            <?php if (!empty($comentario['respostas']) && count($comentario['respostas']) > 0): ?>
                <div class="ml-3 border-l-2 comment-border pl-2">
                    <?php renderizarComentarios($comentario['respostas'], $id_post, $nivel + 1); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php
    endforeach;
}
?>

<!-- SCRIPT PARA TOGGLE DO FORMULÁRIO DE RESPOSTA -->
<script>
    function toggleReply(button) {
        const replyForm = button.closest('.group').querySelector('.reply-form');
        if (replyForm) {
            replyForm.classList.toggle('hidden');
            if (!replyForm.classList.contains('hidden')) {
                replyForm.querySelector('textarea').focus();
            }
        }
    }

    // Detectar tema do sistema ou localStorage (sincronizado com outras páginas)
    window.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme) {
            document.body.setAttribute('data-theme', savedTheme);
        } else {
            // Detectar preferência do sistema
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.body.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
        }
    });

    // Escutar mudanças no localStorage (sincronização entre abas)
    window.addEventListener('storage', function(e) {
        if (e.key === 'theme') {
            document.body.setAttribute('data-theme', e.newValue);
        }
    });
</script>