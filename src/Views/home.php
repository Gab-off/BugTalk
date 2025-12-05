<?php require_once __DIR__ . '/layouts/head.php'; ?>
    <body class="font-mono max-w-[1440px] mx-auto items-center h-screen px-2 md:px-8 lg:px-12 bg-gradient-to-b from-[#E9FCFF] to-[#A5EAF6] dark:from-[#01090B] dark:to-[#003842] overflow-hidden">
    <?php require_once __DIR__ . '/layouts/header.php'; ?>
    <div class="text-white lg:mt-2 lg:grid lg:grid-cols-[200px_1fr_fit-content(400px)] lg:gap-6 lg:items-start">
        <aside class="bg-[#60E5FD] dark:bg-cyan-950 border-none dark:border border-cyan-400 rounded-md hidden px-2 lg:block">
            <form action="" id="bugtagsForm" class="">
                <fieldset class="flex flex-col gap-2 text-[#005f70] dark:text-white">
                    <legend class="py-4 w-full mb-2 text-center border-b border-b-cyan-600 dark:border-b-cyan-50 uppercase">Bugtags</legend>

                    <?php if (empty($tags)): ?>
                        <p>Nenhuma tag encontrada</p>
                    <?php else: ?>
                        <?php foreach ($tags as $tag): ?>
                            <div class="flex justify-between border-b border-cyan-500">
                                <label for="tag_<?= htmlspecialchars($tag['id']) ?>">
                                    <?= htmlspecialchars($tag['nome']) ?>
                                </label>
                                <input
                                        class="appearance-none accent-blue-200 w-5 h-5 border border-blue-500 rounded-md checked:bg-blue-200 transition checked:border-blue-700"
                                        type="checkbox"
                                        id="tag_<?= htmlspecialchars($tag['id']) ?>"
                                        name="tags[]"
                                        value="<?= htmlspecialchars($tag['id']) ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </fieldset>
            </form>
        </aside>

        <div class="flex flex-col h-[calc(100vh-100px)]">

            <section
                    class="my-2 flex items-center justify-between md:items-center md:py-4 lg:justify-center lg:py-2 gap-2 relative">
                <div>
                    <!-- TODO: Colocar o javascript para funcionar o checkbox menu-->
                    <button id="bugtagsBtn"
                            class=" text-sm px-2 py-1 md:px-4 md:py-2 border border-cyan-500 rounded-md text-cyan-400 lg:hidden
             focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-transparent
             transition">
                        <span class="hidden md:inline uppercase">bugtags</span><span
                                class="inline md:hidden">Tags</span>
                    </button>
                </div>

                <div class="">
                    <a href="#"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 text-cyan-800 bg-[#8FEEFF] dark:bg-transparent dark:border dark:border-cyan-500 rounded-md dark:text-white">Recentes</a>
                    <a href="#"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 text-cyan-800 bg-[#8feeff] dark:bg-transparent dark:border dark:border-cyan-500 rounded-md dark:text-white">Em
                        alta</a>
                    <a href="#"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 text-cyan-800 bg-[#8feeff] dark:bg-transparent dark:border dark:border-cyan-500 rounded-md dark:text-white">Ajuda</a>
                    <a href="/post/criar"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 bg-cyan-200 border border-cyan-500 rounded-md text-cyan-800 md:hidden">+</a>
                    <a href="/post/criar"
                       class="text-sm px-2 py-1 md:px-4 md:py-2 bg-cyan-200 border border-cyan-500 rounded-md text-cyan-800 hidden md:inline">Postar</a>
                </div>
            </section>
            <main class="text-[#005f70] dark:text-white overflow-y-auto flex-1 space-y-4 pb-8">
                <?php if (empty($posts)): ?>
                    <h2 class="t">Nenhum post encontrado</h2>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <article
                                class="bg-white dark:bg-transparent grid grid-cols-[max-content_1fr_max-content] gap-4 gap-y-2 items-center
                                border-y dark:border-y-cyan-500 md:border border-[#4ad7f0] dark:md:border-cyan-500 md:rounded-md p-3">

                            <!--user icon image-->
                            <!--TODO: add user page-->
                            <a href="#">
                                <img src="/assets/imgs/icons/icon_user.svg" alt="imagem do usuário" class="w-10 h-10
                                object-contain p-1 ">
                            </a>

                            <!-- name user and title post-->
                            <div class="grid md:gap-3 md:px-2">

                                <!--TODO: add user page-->
                                <a href="#">
                                    <p class="text-sm hover:underline hover:text-cyan-500 transition"><?= htmlspecialchars($post['autor']) ?></p>
                                </a>
                                <a href="/post/ver?id=<?= $post['id'] ?>">
                                    <h2 class="font-bold text-sm md:text-xl  hover:underline hover:text-cyan-500
                                    transition">
                                        <?= htmlspecialchars($post['titulo']) ?>
                                    </h2>
                                </a>
                            </div>

                            <div class="flex gap-3 justify-self-end">
                                <a href="#" class="w-5 h-5 md:w-7 md:h-7"><img
                                            src="/assets/imgs/icons/share_icon_dark.svg"
                                            alt="ícone de compartilhar post"></a>
                                <a href="#" class="w-5 h-5 md:w-7 md:h-7"><img
                                            src="/assets/imgs/icons/favorite.svg"
                                            alt="ícone de favoritar postagem"></a>
                            </div>

                            <div class="row-start-3 md:row-start-2 justify-self-center flex flex-col items-center gap-1">
                                <?php if (isset($_SESSION['id_usuario'])): ?>
                                    <!-- Usuário logado - botão funcional -->
                                    <button type="button"
                                            class="upvote-btn transition-colors <?= $post['user_has_voted'] ? 'text-cyan-400' : 'text-cyan-500/50' ?> hover:text-cyan-300 cursor-pointer"
                                            data-postid="<?= $post['id'] ?>">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M7 14H5v5h5v2H3v-7H1l6-6 6 6h-2v-2H7v2zm10-2h2V7h-5V5h7v7h2l-6 6-6-6h2v2h4v-2z"/>
                                        </svg>
                                    </button>
                                <?php else: ?>
                                    <!-- Usuário não logado - botão com tooltip -->
                                    <div class="relative group">
                                        <button type="button"
                                                class="text-cyan-500/30 cursor-not-allowed"
                                                disabled
                                                title="Faça login para votar">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M7 14H5v5h5v2H3v-7H1l6-6 6 6h-2v-2H7v2zm10-2h2V7h-5V5h7v7h2l-6 6-6-6h2v2h4v-2z"/>
                                            </svg>
                                        </button>
                                        <!-- Tooltip -->
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-slate-800 text-cyan-300 text-xs rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                            <a href="/login" class=" hover:text-cyan-400 underline">Faça login</a>
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <span class="upvote-count text-cyan-400 text-sm font-semibold"
                                      id="upvote_count_<?= $post['id'] ?>">
        <?= $post['upvotes'] ?>
    </span>
                            </div>

                            <div class="col-span-3 md:col-span-2 bg-[#b0f2fe] dark:bg-[#092A2F] dark:border dark:border-cyan-500 rounded-md">
                                <a href="">
                                    <p class="text-sm md:text-lg text-[#005f70] dark:text-white line-clamp-6 px-2 py-1">
                                        <?= htmlspecialchars($post['conteudo']) ?>
                                    </p>
                                </a>
                            </div>

                            <div class="col-start-2">
                                <?php if (!empty($post['tags'])): ?>
                                    <?php foreach ($post['tags'] as $tagName): ?>
                                        <!--TODO: (talvez) estruturar o link para fazer pela tag clicada-->
                                        <a href="#"
                                           class=" rounded-2xl border border-cyan-700 p-1 text-xs lowercase"><?= htmlspecialchars($tagName) ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-4 px-2 pt-2 border-t border-cyan-500/30 col-start-3">
                                <a href="/post/ver?id=<?= $post['id'] ?>"
                                   class="flex items-center gap-2 hover:text-cyan-400 transition-colors">
                                    <img src="/assets/imgs/icons/comment.svg" alt="Comentar" class="w-5 h-5">
                                    <!-- TODO: coletar os dados para mostrar-->
                                    <span class="text-sm">12 <span class="hidden md:inline">comentários</span></span>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </main>
        </div>

        <div class="hidden border rounded-md border-cyan-300 lg:block lg:justify-self-end px-2 py-4">
            <h2 class="text-center mb-4 text-cyan-300 font-semibold">Atividades recentes</h2>
            <?php if (empty($atividades)): ?>
                <p class="text-cyan-100 text-sm">Nenhuma atividade recente</p>
            <?php else: ?>
                <?php foreach ($atividades as $atividade): ?>
                    <div class="grid grid-cols-[50px_1fr] gap-2 border mb-2 border-cyan-300 rounded p-2 hover:bg-cyan-950/30 transition">
                        <div class="flex items-center justify-center bg-cyan-500 rounded overflow-hidden">
                            <img src="/assets/imgs/icons/icon_user.svg" alt="avatar" class="w-full h-full object-cover">
                        </div>

                        <div class="text-xs px-1 text-cyan-100">
                            <p class="">
                                <a href="/post/ver?id=<?= $atividade['id_alvo'] ?>"
                                   class="text-cyan-400 hover:underline">
                                    <?= htmlspecialchars($atividade['autor']) ?>
                                </a>
                            </p>
                            <p class="text-cyan-200">
                                <?= htmlspecialchars($atividade['tipo_evento']) ?>
                            </p>
                            <p class="text-cyan-100 truncate">
                                "<?= htmlspecialchars(substr($atividade['titulo_alvo'], 0, 30)) ?>..."
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const upvoteButtons = document.querySelectorAll('.upvote-btn');

            upvoteButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Verifica se está logado
                    if (this.disabled) {
                        alert('Você precisa fazer login para votar');
                        return;
                    }

                    const postId = this.getAttribute('data-postid');
                    const btnEl = this;
                    const countEl = document.getElementById('upvote_count_' + postId);

                    // Desabilita o botão durante a requisição
                    btnEl.disabled = true;
                    btnEl.style.opacity = '0.5';

                    // Faz a requisição AJAX
                    fetch('/post/vote', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'post_id=' + encodeURIComponent(postId)
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro na resposta do servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Atualiza o contador
                                countEl.textContent = data.novo_total;

                                // Alterna a cor do botão baseado no estado do voto
                                if (data.user_has_voted) {
                                    btnEl.classList.remove('text-cyan-500/50');
                                    btnEl.classList.add('text-cyan-400');
                                } else {
                                    btnEl.classList.remove('text-cyan-400');
                                    btnEl.classList.add('text-cyan-500/50');
                                }

                                // Animação de feedback
                                countEl.style.transform = 'scale(1.3)';
                                setTimeout(() => {
                                    countEl.style.transform = 'scale(1)';
                                }, 200);

                            } else {
                                // Trata erros específicos
                                if (data.error === 'not_logged_in') {
                                    alert('Você precisa fazer login para votar');
                                    window.location.href = '/login';
                                } else {
                                    alert('Erro ao votar: ' + (data.error || 'Erro desconhecido'));
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Erro de conexão. Tente novamente.');
                        })
                        .finally(() => {
                            // Reabilita o botão
                            btnEl.disabled = false;
                            btnEl.style.opacity = '1';
                        });
                });
            });

            // Adiciona transição suave ao contador
            document.querySelectorAll('.upvote-count').forEach(el => {
                el.style.transition = 'transform 0.2s ease';
            });
        });
    </script>
    </body>
<?php require_once __DIR__ . '/layouts/footer.php'; ?>