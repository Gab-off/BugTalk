<?php require_once __DIR__ . '/../layouts/head.php'; ?>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12
             bg-gradient-to-b from-[#E6F9FF] to-[#A5ECFF] text-sky-900
             dark:bg-gradient-to-b dark:from-[#01090B] dark:to-[#003842] dark:text-slate-100">
<main class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md rounded-xl mt-5 md:mt-10 p-8 shadow-lg
                bg-white/90 border border-sky-300
                dark:bg-slate-900 dark:border-2 dark:border-cyan-400">
        <h1 class="text-xl md:text-3xl font-bold text-center mb-2 tracking-widest
                   text-sky-800 dark:text-white">
            Cadastre-se
        </h1>

        <p class="text-center text-sm mb-6 text-sky-800/80 dark:text-cyan-200">
            Ao clicar em cadastrar você concorda com nossos
            <a href="#" class="text-sky-700 hover:underline dark:text-cyan-400">
                termos de serviço
            </a>
            e com a nossa
            <a href="#" class="text-sky-700 hover:underline dark:text-cyan-400">
                política de privacidade
            </a>
        </p>

        <!-- MENSAGEM DE ERRO -->
        <?php if (isset($erro)): ?>
            <div class="mb-4 rounded-lg p-3 text-sm
                        bg-red-50 border border-red-400 text-red-700
                        dark:bg-red-900/30 dark:border-red-500 dark:text-red-300">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/cadastro" method="POST" class="space-y-4">
            <!-- NOME -->
            <div>
                <input type="text" name="nome" placeholder="Nome do usuário" required
                       class="w-full p-3 rounded-lg border bg-white text-sky-900
                              border-sky-300 placeholder-sky-400
                              focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400
                              dark:bg-transparent dark:text-white dark:border-cyan-500
                              dark:placeholder-cyan-300 dark:focus:outline-cyan-300 transition">
            </div>

            <!-- EMAIL -->
            <div>
                <input type="email" name="email" placeholder="Email" required
                       class="w-full p-3 rounded-lg border bg-white text-sky-900
                              border-sky-300 placeholder-sky-400
                              focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400
                              dark:bg-transparent dark:text-white dark:border-cyan-500
                              dark:placeholder-cyan-300 dark:focus:outline-cyan-300 transition">
            </div>

            <!-- SENHA -->
            <div>
                <input type="password" name="senha" placeholder="Senha" required
                       class="w-full p-3 rounded-lg border bg-white text-sky-900
                              border-sky-300 placeholder-sky-400
                              focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400
                              dark:bg-transparent dark:text-white dark:border-cyan-500
                              dark:placeholder-cyan-300 dark:focus:outline-cyan-300 transition">
            </div>

            <!-- CONFIRMAR SENHA (comentado, mantido igual) -->
            <!--
            <div>
                <input type="password" name="senha_confirm" id="senha_confirm" placeholder="Confirmar a senha" required
                       class="w-full p-3 rounded-lg border bg-white text-sky-900
                              border-sky-300 placeholder-sky-400
                              focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400
                              dark:bg-transparent dark:text-white dark:border-cyan-500
                              dark:placeholder-cyan-300 dark:focus:outline-cyan-300 transition">
            </div>
            -->

            <!-- BOTÃO CADASTRAR -->
            <button type="submit"
                    class="w-full py-3 mt-6 rounded-lg font-semibold transition
                           bg-sky-500 text-white shadow-md shadow-sky-300
                           hover:bg-sky-600 hover:shadow-lg hover:shadow-sky-400
                           dark:bg-gradient-to-b dark:from-cyan-700 dark:to-cyan-900
                           dark:hover:from-cyan-600 dark:hover:to-cyan-800">
                Cadastrar
            </button>
        </form>

        <!-- LINK PARA LOGIN -->
        <div class="mt-6 text-center text-sky-800/80 dark:text-cyan-200">
            Já tem uma conta?
            <a href="/login" class="font-semibold text-sky-700 hover:underline dark:text-cyan-400">
                Entre aqui
            </a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

</body>
</html>
