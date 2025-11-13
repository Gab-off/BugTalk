<header class="mt-4 grid grid-cols-3 items-center md:pb-2 md:grid md:grid-cols-2 lg:gap-4 lg:grid lg:grid-cols-3 lg:items-center lg:self-center">
    <nav class="flex gap-4 hidden md:flex md:items-center">
        <?php if (isset($_SESSION['id_usuario'])) : ?>
            <div class="buttons_div">
                <!--TODO: add user page link-->
                <a href="/"
                   class="px-4 py-2 bg-cyan-950 rounded-md text-white"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></a>
                <a href="/logout" class="px-4 py-2 bg-cyan-800 rounded-md text-white">Deslogar</a>
            </div>
        <?php else : ?>
            <a href="/login" class="px-4 py-2 bg-cyan-950 rounded-md text-white">Login</a>
            <a href="/cadastro" class="px-4 py-2 bg-cyan-800 rounded-md text-white">Cadastrar-se</a>
        <?php endif; ?>
    </nav>

    <button id="hamburger-button" class="visible md:hidden hamburger-icon relative h-8 w-8 cursor-pointer">
        <span class="bg-white block absolute h-0.5 w-5 rounded-full"></span>
        <span class="bg-white block absolute h-0.5 w-5 rounded-full"></span>
        <span class="bg-white block absolute h-0.5 w-5 rounded-full"></span>
    </button>

    <a href="/" class="md:justify-self-end lg:h-auto lg:scale-100 lg:justify-self-center">
        <img src="/assets/imgs/logotipo_blackTheme.svg" alt="logo Bugtalk" class="md:max-w-72">
    </a>

    <!--    Right col-->
    <form action="" id="search-form"
          class="hidden mt-2 col-span-3 w-full md:grid md:col-span-2 lg:mt-0 lg:w-auto lg:col-auto lg:justify-self-end">
        <div class="w-full lg:max-w-sm lg:min-w-[300px] bg-transparent text-cyan-500 border rounded-2xl border-cyan-300 relative">
            <span><img src="/assets/imgs/icons/search.svg" alt="ícone de lupa"
                       class="absolute start-0 top-1 ps-3 flex items-center pointer-events-none"></span>
            <input type="text" placeholder="Procure uma postagem ou usuário aqui"
                   class="text-sm px-4 py-2 ps-12 w-full placeholder:text-cyan-100 transition rounded-2xl focus:outline-cyan-300 focus:border-none">
        </div>
    </form>
    <!--    Search icon mobile-->
    <a id="search-toggle-button" class="cursor-pointer col-3 row-1 justify-self-end md:hidden"><span><img
                    src="/assets/imgs/icons/search.svg" alt="ícone de lupa"></span></a>
</header>