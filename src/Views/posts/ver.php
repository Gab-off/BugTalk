<?php
require_once __DIR__ . '/../layouts/head-visualizar.php';
?>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12 bg-gradient-to-b from-[#01090B] to-[#003842]">

<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="flex gap-6 mt-6 pb-8">
    <!-- COLUNA PRINCIPAL - POST E COMENTÁRIOS -->
    <div class="flex-1 min-w-0">

        <!-- POST PRINCIPAL -->
        <article class="border-2 border-cyan-400 rounded-xl bg-slate-900 p-6 mb-6">
            <!-- HEADER DO POST -->
            <div class="flex gap-4 mb-4">
                <img src="/assets/img/user.svg" alt="Avatar" class="w-10 h-10 rounded-full bg-cyan-500 p-1">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-cyan-300"><?= htmlspecialchars($post['autor']) ?></span>
                        <span class="text-xs text-cyan-200/60">u/<?= htmlspecialchars($post['autor']) ?></span>
                    </div>
                    <span class="text-xs text-cyan-200/50">há 2 horas</span>
                </div>
            </div>

            <!-- TÍTULO -->
            <h1 class="text-2xl font-bold text-white mb-4"><?= htmlspecialchars($post['titulo']) ?></h1>

            <!-- CONTEÚDO -->
            <div class="text-cyan-100 mb-6 leading-relaxed">
                <?= nl2br(htmlspecialchars($post['conteudo'])) ?>
            </div>

            <!-- BLOCO DE CÓDIGO (se houver) -->
            <?php if (!empty($post['codigo'])): ?>
                <div class="bg-slate-800 rounded-lg border border-cyan-500/50 mb-6 overflow-hidden">
                    <div class="bg-slate-700/50 px-4 py-2 flex justify-between items-center">
                        <span class="text-xs font-semibold text-cyan-300"><?= htmlspecialchars($post['linguagem']) ?></span>
                    </div>
                    <pre class="p-4 overflow-x-auto"><code class="language-<?= htmlspecialchars($post['linguagem']) ?>"><?= htmlspecialchars($post['codigo']) ?></code></pre>
                </div>
            <?php endif; ?>

            <!-- IMAGEM (se houver) -->
            <?php if (!empty($post['imagem_url'])): ?>
                <img src="<?= htmlspecialchars($post['imagem_url']) ?>" alt="Post image" class="rounded-lg mb-6 max-w-full">
            <?php endif; ?>

            <!-- TAGS -->
            <?php if (!empty($post['tags']) && count($post['tags']) > 0): ?>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php foreach ($post['tags'] as $tag): ?>
                        <span class="px-3 py-1 bg-cyan-950/50 border border-cyan-500/50 rounded-full text-xs text-cyan-300 hover:bg-cyan-950 transition cursor-pointer">
                            <?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- FOOTER COM AÇÕES -->
            <div class="flex gap-6 pt-4 border-t border-cyan-500/30">
                <!-- UPVOTE -->
                <form action="/post/vote" method="POST" class="flex items-center gap-2 hover:bg-cyan-950/30 px-3 py-2 rounded transition cursor-pointer">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" class="flex items-center gap-2 text-sm text-cyan-300 hover:text-cyan-200 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 14c-1.66 0-3-1.34-3-3 0-1.31.84-2.41 2-2.83V4c0-.83.67-1.5 1.5-1.5S9 3.17 9 4v4.17c1.16.42 2 1.52 2 2.83 0 1.66-1.34 3-3 3zm13.71-9.71L12 .71 2.29 10.29C1.08 11.5 1.08 13.42 2.29 14.63l9.71 9.71c.39.39 1.02.39 1.41 0l9.71-9.71c1.21-1.21 1.21-3.13 0-4.34zM12 20.58L4.12 12.7c-.39-.39-.39-1.02 0-1.41L12 3.41l7.88 7.88c.39.39.39 1.02 0 1.41L12 20.58z"/>
                        </svg>
                        <span><?= $post['upvotes'] ?></span>
                    </button>
                </form>

                <!-- COMENTÁRIOS -->
                <a href="#comentarios" class="flex items-center gap-2 text-sm text-cyan-300 hover:bg-cyan-950/30 px-3 py-2 rounded transition hover:text-cyan-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                    <span>12</span>
                </a>

                <!-- COMPARTILHAR -->
                <a href="#" class="flex items-center gap-2 text-sm text-cyan-300 hover:bg-cyan-950/30 px-3 py-2 rounded transition hover:text-cyan-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.52.47 1.2.77 1.96.77 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.52 9.31 6.84 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.84 0 1.52-.31 2.04-.81l7.12 4.16c-.057.21-.092.43-.092.67 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                    </svg>
                </a>
            </div>
        </article>

        <!-- SEÇÃO DE COMENTÁRIOS -->
        <div id="comentarios" class="space-y-2">
            <h2 class="text-lg font-bold text-cyan-300 mb-6">Comentários</h2>

            <!-- FORMULÁRIO DE COMENTÁRIO (se logado) -->
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <form action="/comentario/criar" method="POST" class="mb-6 bg-slate-900 border border-cyan-500/30 rounded-lg p-4">
                    <input type="hidden" name="id_pai" value="<?= htmlspecialchars($post['id']) ?>">
                    <input type="hidden" name="tipo_pai" value="Post">
                    <textarea name="texto" placeholder="Qual é sua opinião?" rows="3"
                              class="w-full bg-slate-800 border border-cyan-500/50 rounded px-4 py-2 text-cyan-100 placeholder-cyan-500/50 focus:outline-cyan-300 resize-none mb-3"></textarea>
                    <button type="submit" class="bg-cyan-700 hover:bg-cyan-600 text-white px-6 py-2 rounded font-semibold transition">
                        Comentar
                    </button>
                </form>
            <?php else: ?>
                <div class="mb-6 bg-slate-900/50 border border-cyan-500/30 rounded-lg p-4 text-center">
                    <p class="text-cyan-300"><a href="/login" class="text-cyan-400 hover:underline">Faça login</a> para comentar</p>
                </div>
            <?php endif; ?>

            <!-- LISTA DE COMENTÁRIOS -->
            <div class="space-y-2">
                <?php if (empty($comentarios)): ?>
                    <div class="text-center py-8 text-cyan-300/60">
                        <p>Nenhum comentário ainda. Seja o primeiro!</p>
                    </div>
                <?php else: ?>
                    <?php renderizarComentarios($comentarios, $post['id']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SIDEBAR DIREITA (INFORMAÇÕES) -->
    <aside class="hidden lg:block w-80">
        <div class="bg-slate-900 border border-cyan-500/30 rounded-lg p-4 sticky top-20">
            <h3 class="font-bold text-cyan-300 mb-4">Informações</h3>
            <div class="space-y-3 text-sm text-cyan-100/80">
                <div>
                    <span class="text-cyan-400">Criado por:</span>
                    <p class="font-semibold text-cyan-300"><?= htmlspecialchars($post['autor']) ?></p>
                </div>
                <div>
                    <span class="text-cyan-400">Linguagem:</span>
                    <p class="font-semibold text-cyan-300"><?= htmlspecialchars($post['linguagem']) ?></p>
                </div>
                <div>
                    <span class="text-cyan-400">Upvotes:</span>
                    <p class="font-semibold text-cyan-300"><?= $post['upvotes'] ?></p>
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
            <div class="bg-slate-900/50 border border-cyan-500/20 rounded-lg p-4 hover:border-cyan-500/50 transition <?= $nivel > 0 ? 'mb-1' : 'mb-3' ?>">
                <!-- HEADER -->
                <div class="flex gap-3 mb-3">
                    <img src="/assets/img/user.svg" alt="Avatar" class="w-8 h-8 rounded-full bg-cyan-500 p-0.5 flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-cyan-300 text-sm"><?= htmlspecialchars($comentario['autor']) ?></span>
                            <span class="text-xs text-cyan-200/50">u/<?= htmlspecialchars($comentario['autor']) ?></span>
                        </div>
                        <span class="text-xs text-cyan-200/50">há 1 hora</span>
                    </div>
                </div>

                <!-- CONTEÚDO -->
                <p class="text-cyan-100 text-sm mb-3 leading-relaxed">
                    <?= nl2br(htmlspecialchars($comentario['texto'])) ?>
                </p>

                <!-- AÇÕES -->
                <div class="flex gap-4 text-xs opacity-0 group-hover:opacity-100 transition">
                    <button class="text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M7 14c-1.66 0-3-1.34-3-3 0-1.31.84-2.41 2-2.83V4c0-.83.67-1.5 1.5-1.5S9 3.17 9 4v4.17c1.16.42 2 1.52 2 2.83 0 1.66-1.34 3-3 3z"/></svg>
                        Upvote
                    </button>
                    <button class="text-cyan-400 hover:text-cyan-300 flex items-center gap-1" onclick="toggleReply(this)">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        Responder
                    </button>
                </div>

                <!-- FORMULÁRIO DE RESPOSTA (HIDDEN) -->
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <div class="reply-form hidden mt-3 pt-3 border-t border-cyan-500/20">
                        <form action="/comentario/criar" method="POST" class="space-y-2">
                            <input type="hidden" name="id_pai" value="<?= htmlspecialchars($comentario['id']) ?>">
                            <input type="hidden" name="tipo_pai" value="Comentario">
                            <input type="hidden" name="id_post" value="<?= htmlspecialchars($id_post) ?>">
                            <textarea name="texto" placeholder="Escrever uma resposta..." rows="2"
                                      class="w-full bg-slate-800 border border-cyan-500/50 rounded px-3 py-2 text-cyan-100 placeholder-cyan-500/50 focus:outline-cyan-300 resize-none text-sm"></textarea>
                            <button type="submit" class="bg-cyan-700 hover:bg-cyan-600 text-white px-4 py-1 rounded text-sm font-semibold transition">
                                Responder
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RESPOSTAS ANINHADAS (RECURSÃO) -->
            <?php if (!empty($comentario['respostas']) && count($comentario['respostas']) > 0): ?>
                <div class="ml-2 border-l-2 border-cyan-500/30">
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
</script>
