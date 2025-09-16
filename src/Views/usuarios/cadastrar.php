<?php require_once __DIR__ . '/../layouts/head.php'; ?>
    <body class="cadastrar_bg">
    <main class="container main_form">
        <div class="all_container">

            <img src="/assets/imgs/logo_bug.svg" alt="" class="logo_bug">
            <div class="form_container">
                <h1 class="form_title">Cadastre-se</h1>
                <p class="terms_text">Ao clicar em cadastrar você concorda com nossos <span>termos de serviço</span> e com a nossa <span>política de
                        privacidade</span></p>
                <?php if (isset($erro)): ?>
                    <div><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <form action="/cadastro" method="post">
                    <div>
                        <input type="text" name="nome" placeholder="Nome do usuário" required class="form_input">
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Email" required class="form_input">
                    </div>
                    <div>
                        <input type="password" name="senha" placeholder="Senha" required class="form_input">
                    </div>
                    <button type="submit" class="form-button">Cadastrar</button>
                    <p class="have_account">
                        Já tem uma conta? <a href="/login" class="form-link">Entre aqui</a>
                    </p>
                </form>
            </div>
                <img src="/assets/imgs/logo_bug_inverted.svg" alt="" class="logo_bug">

        </div>
    </main>
    </body>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>