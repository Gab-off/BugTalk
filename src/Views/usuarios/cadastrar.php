<?php require_once __DIR__ . '/../layouts/head.php'; ?>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12 bg-gradient-to-b from-[#01090B] to-[#003842]">
<main class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md rounded-xl border-2 border-cyan-400 bg-slate-900 mt-5 md:mt-10 p-8 shadow-lg">
        <h1 class="text-xl md:text-3xl font-bold text-white text-center mb-2 tracking-widest">Cadastre-se</h1>

        <p class="text-center text-cyan-200 text-sm mb-6">
            Ao clicar em cadastrar você concorda com nossos
            <a href="#" class="text-cyan-400 hover:underline">termos de serviço</a>
            e com a nossa
            <a href="#" class="text-cyan-400 hover:underline">política de privacidade</a>
        </p>

        <!-- MENSAGEM DE ERRO -->
        <?php if (isset($erro)): ?>
            <div class="bg-red-900/30 border border-red-500 rounded-lg p-3 mb-4 text-red-300 text-sm">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="/cadastro" method="POST" class="space-y-4">
            <!-- NOME -->
            <div>
                <input type="text" name="nome" placeholder="Nome do usuário" required
                       class="w-full p-3 rounded-lg border border-cyan-500 bg-transparent text-white placeholder-cyan-300 focus:outline-cyan-300 transition">
            </div>

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

            <!-- CONFIRMAR SENHA -->
<!--            <div>-->
<!--                <input type="password" name="senha_confirm" id="senha_confirm" placeholder="Confirmar a senha" required-->
<!--                       class="w-full p-3 rounded-lg border border-cyan-500 bg-transparent text-white placeholder-cyan-300 focus:outline-cyan-300 transition">-->
<!--            </div>-->

            <!-- BOTÃO CADASTRAR -->
            <button type="submit"
                    class="w-full bg-gradient-to-b from-cyan-700 to-cyan-900 text-white py-3 rounded-lg font-semibold hover:from-cyan-600 hover:to-cyan-800 transition mt-6">
                Cadastrar
            </button>
        </form>

        <!-- LINK PARA LOGIN -->
        <div class="mt-6 text-center text-cyan-200">
            Já tem uma conta?
            <a href="/login" class="text-cyan-400 font-semibold hover:underline">
                Entre aqui
            </a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

</body>
</html>
