<?php require_once __DIR__ . '/../layouts/head.php'; ?>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12 bg-gradient-to-b from-[#01090B] to-[#003842]">

<main class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md rounded-xl border-2 border-cyan-400 bg-slate-900 p-8 shadow-lg">
        <h1 class="text-3xl font-bold text-white text-center mb-8 tracking-widest uppercase">Entrar</h1>

        <!-- MENSAGEM DE ERRO -->
        <?php if (isset($erro)): ?>
            <div class="bg-red-900/30 border border-red-500 rounded-lg p-3 mb-4 text-red-300 text-sm">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-4">
            <!-- EMAIL -->
            <div>
                <input type="email" name="email" placeholder="Email" required
                       class="w-full p-3 rounded-lg border border-cyan-500 bg-transparent text-white placeholder-cyan-300 focus:outline-cyan-300 transition">
            </div>

            <!-- SENHA -->
            <div>
                <input type="password" name="senha" placeholder="Senha" required
                       class="w-full p-3 rounded-lg border border-cyan-500 bg-transparent text-white placeholder-cyan-300 focus:outline-cyan-300 transition">
            </div>

            <!-- BOTÃO ENTRAR -->
            <button type="submit"
                    class="w-full bg-gradient-to-b from-cyan-700 to-cyan-900 text-white py-3 rounded-lg font-semibold hover:from-cyan-600 hover:to-cyan-800 transition mt-6">
                Entrar
            </button>
        </form>

        <!-- LINK PARA CADASTRO -->
        <div class="mt-6 text-center text-cyan-200">
            Não tem uma conta?
            <a href="/cadastro" class="text-cyan-400 font-semibold hover:underline">
                Crie aqui!
            </a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

</body>
</html>
