<?php
session_start();
require 'conexao.php';

$posts = [];
try {
    $query = '
      MATCH (u:Usuario)-[:PUBLICOU]->(p:Post) 
      RETURN p.titulo AS titulo, p.conteudo AS conteudo, p.data_criacao AS data, u.nome AS autor, id(p) AS id_post
      ORDER BY p.data_criacao DESC
    ';
    $result = $client->run($query);
    foreach ($result as $record) {
        $posts[] = [
            'titulo' => $record->get('titulo'),
            'conteudo' => $record->get('conteudo'),
            'autor' => $record->get('autor'),
            'data' => $record->get('data'),
            'id_post' => $record->get('id_post')
        ];
    }
} catch (\Exception $e) {
    die("Erro ao buscar posts: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bugtalk - Seu Fórum de TI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            font-family: 'VCR OSD Mono', monospace;
        }

        .post-content {
            font-family: 'ProFont IIx Nerd Font', monospace;
            font-size: 14px;
        }
    </style>
</head>
<body class="bg-[#08262B] text-gray-200">

<div class=" ">
    <header class="mx-auto p-4 flex justify-between items-center">
        <div class="flex items-center gap-x-8">

            <!--            MENU BUTTONS-->
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <div class="flex items-center gap-x-4">
                    <a href="cadastrar.php"
                       class="bg-cyan-400 hover:bg-cyan-600 text-gray-900  py-2 px-6 rounded-md transition-colors">Inscreva-se</a>
                    <a href="login.php"
                       class="bg-cyan-900 hover:bg-cyan-600 text-white  py-2 px-6 rounded-md transition-colors">Login</a>
                </div>
            <?php elseif (isset($_SESSION['usuario_id'])): ?>
                <div class="flex items-center gap-x-4">
                    <span class="text-white font-semibold">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</span>

                    <?php if ($_SESSION['usuario_isAdmin'] === true): ?>
                        <a href="admin.php" class="text-yellow-400 hover:text-yellow-300 font-bold"
                           title="Área Administrativa">Admin</a>
                    <?php endif; ?>

                    <a href="criar_post.php"
                       class="bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-2 px-4 rounded">Criar Post</a>
                    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg">Sair</a>

                </div>
            <?php endif; ?>


        </div>

        <!--        LOGO-->
        <a href="index.php">
            <img src="src/img/logo-oficialWhite.svg" alt="Bugtalk" class="h-20">
        </a>

        <!--        PESQUISA-->
        <div class="flex items-center gap-x-6">
            <form action="/pesquisa.php" method="GET" class="w-80">
                <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </span>
                    <input
                            type="search"
                            name="q"
                            placeholder="Pesquisar..."
                            class="w-full rounded-full border-2 border-transparent bg-[#127a8c] py-2 pl-12 pr-4 text-white placeholder-gray-400 focus:border-cyan-500 focus:outline-none focus:ring-0"
                    >
                </div>
            </form>

        </div>
    </header>

    <div class="flex gap-6">
        <aside class="w-1/5">
            <div class="bg-[#007C92] p-4 rounded-r-md">
                <h3 class="font-bold mb-4">BugTags</h3>
                <ul>
                    <li class="mb-2"><a href="#" class="hover:text-cyan-400">PHP</a></li>
                    <li class="mb-2"><a href="#" class="hover:text-cyan-400">Javascript</a></li>
                </ul>
            </div>
        </aside>

        <main class="w-3/5">
            <?php if (empty($posts)): ?>
                <div class="bg-[#12AAC4] p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-semibold">Nenhum post encontrado.</h3>
                    <p class="mt-2">Seja o primeiro a criar
                        um! <?php if (isset($_SESSION['usuario_id'])) echo '<a href="criar_post.php" class="text-cyan-400 underline">Crie um post agora</a>'; else echo '<a href="login.php" class="text-cyan-400 underline">Faça login para postar</a>'; ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="bg-[#12AAC4] p-6 rounded-sm mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <img src="src/img/icon_userDefault.svg" alt="">
                            <span class=""><?= htmlspecialchars($post['autor']) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-2xl">
                            <a href="ver_post.php?id=<?= $post['id_post'] ?>" class="hover:text-cyan-400">
                                <?= htmlspecialchars($post['titulo']) ?>
                            </a>
                            <div class="flex gap-6 pr-1">
                                <?php if (isset($_SESSION['usuario_id'])): ?>
                                    <button title="Comentar"><img class="w-6" src="src/img/to_share_white.png" alt=""></button>
                                    <button title="Favoritar"><img class="w-6" src="src/img/favorite_white.png" alt=""></button>
                                <?php else: ?>
                                    <a href="login.php" title="Faça login para interagir"><img class="w-6"
                                                src="src/img/to_share_white.png" alt=""></a>
                                    <a href="login.php" title="Faça login para interagir"><img class="w-6"
                                                src="src/img/favorite_white.png" alt=""></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <div class="text-cyan-900 bg-cyan-300 flex-1 p-2 rounded-sm">
                                <p class="post-content">
                                    <?= nl2br(htmlspecialchars($post['conteudo']))?>
                                </p>
                            </div>
                            <img src="src/img/upvote_white.svg" alt="">
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>

        <aside class="w-1/5">
            <div class="bg-[#127a8c] p-4 rounded-l-md shadow-lg">
                <h3 class="font-bold mb-4">Atividade Recente</h3>
            </div>
        </aside>
    </div>
</div>

</body>
</html>