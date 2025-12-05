<?php require_once __DIR__ . '/../layouts/head.php'; ?>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12
             bg-gradient-to-b from-[#E6F9FF] to-[#A5ECFF] text-sky-900
             dark:bg-gradient-to-b dark:from-[#01090B] dark:to-[#003842] dark:text-slate-100">

<main class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md rounded-xl p-8 shadow-lg
                bg-white/90 border border-sky-300
                dark:bg-slate-900 dark:border-2 dark:border-cyan-400">
        <h1 class="text-3xl font-bold text-center mb-8 tracking-widest uppercase
                   text-sky-800 dark:text-white">
            Entrar
        </h1>

        <form action="/login" method="POST" class="space-y-4">
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

            <!-- BOTÃO ENTRAR -->
            <button type="submit"
                    class="w-full py-3 mt-6 rounded-lg font-semibold transition
                           bg-sky-500 text-white shadow-md shadow-sky-300
                           hover:bg-sky-600 hover:shadow-lg hover:shadow-sky-400
                           dark:bg-gradient-to-b dark:from-cyan-700 dark:to-cyan-900
                           dark:hover:from-cyan-600 dark:hover:to-cyan-800">
                Entrar
            </button>
        </form>

        <!-- LINK PARA CADASTRO -->
        <div class="mt-6 text-center text-sky-800/80 dark:text-cyan-200">
            Não tem uma conta?
            <a href="/cadastro" class="font-semibold text-sky-700 hover:underline dark:text-cyan-400">
                Crie aqui!
            </a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

</body>
</html>
