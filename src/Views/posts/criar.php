<?php
// criar.php - Versão nova com Tailwind + CodeMirror + Tags dinâmicas
use App\Models\Tag;
require_once __DIR__ . '/../layouts/head-criar-post.php';
require_once __DIR__ . '/../layouts/header.php';

// Buscar tags do banco (você ajusta conforme seu modelo)
$tagModel = new Tag();

// Se houver erro de criação
$erro = $_GET['erro'] ?? null;
?>

<body class="font-mono max-w-[1440px] mx-auto h-full px-2 md:px-8 lg:px-12 bg-gradient-to-b from-[#01090B] to-[#003842]">

<main class="flex flex-col items-center justify-center min-h-screen px-2 py-8">
    <form class="w-full max-w-2xl rounded-xl border-2 border-cyan-400 bg-slate-900 p-6 shadow-lg flex flex-col gap-6"
          action="/post/criar" method="POST" enctype="multipart/form-data">

        <h2 class="text-3xl font-bold text-white text-center mb-2 tracking-widest">Criar post</h2>

        <!-- Mostrar erro se houver -->
        <?php if ($erro): ?>
            <div class="bg-red-900/30 border border-red-500 rounded-lg p-3 text-red-300">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <!-- TÍTULO -->
        <div>
            <input type="text" id="titulo" placeholder="Título" name="titulo" required
                   class="w-full p-2 rounded-lg border border-cyan-500 bg-transparent text-white placeholder-cyan-300 focus:outline-cyan-300 text-lg"/>
        </div>

        <!-- TEXTO DO POST -->
        <div>
            <textarea id="texto" name="texto" placeholder="Texto do post" required rows="4"
                      class="w-full p-2 rounded-lg border border-cyan-500 bg-transparent text-white placeholder-cyan-300 focus:outline-cyan-300 text-base resize-none"></textarea>
        </div>

        <!-- IMAGEM -->
        <div>
            <label for="imagem" class="text-cyan-200 text-lg font-semibold block mb-1">Imagem (opcional)</label>
            <div class="flex flex-col sm:flex-row gap-2 items-stretch">
                <input type="file" id="imagem" name="imagem" accept="image/*"
                       class="flex-1 file:px-4 file:py-2 file:rounded-lg file:border-0 file:text-sm file:bg-cyan-700 file:text-white file:font-semibold
                              border border-cyan-500 rounded-lg bg-slate-800 text-white w-full file:w-full file:cursor-pointer file:mr-0"
                       onchange="previewFileName(this)">
                <div id="nome-arquivo" class="text-xs text-cyan-300 overflow-x-auto min-h-[24px] py-1"></div>
            </div>
        </div>

        <!-- TAGS -->
        <div>
            <label class="text-cyan-200 text-lg font-semibold block mb-2">Tags da Postagem</label>
            <fieldset class="flex flex-wrap gap-3 mb-4">
                <?php if (empty($tags)): ?>
                    <p class="text-cyan-300">Nenhuma tag disponível</p>
                <?php else: ?>
                    <?php foreach ($tags as $tag): ?>
                        <label class="flex items-center gap-2 border rounded-lg px-3 py-1 bg-slate-800 text-cyan-300 cursor-pointer hover:bg-slate-700 transition">
                            <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['id']) ?>"
                                   class="accent-cyan-400 w-5 h-5 rounded transition"/>
                            <?= htmlspecialchars($tag['nome']) ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </fieldset>
        </div>

        <!-- LINGUAGEM DO CÓDIGO -->
        <div>
            <label for="language" class="text-cyan-200 text-lg font-semibold block mb-2">Linguagem do Código</label>
            <select id="language" name="language"
                    class="mb-2 p-2 rounded bg-slate-900 text-cyan-300 border border-cyan-400 font-mono w-full">
                <option value="javascript">JavaScript</option>
                <option value="php">PHP</option>
                <option value="python">Python</option>
                <option value="css">CSS</option>
                <option value="htmlmixed">HTML</option>
            </select>
            <label for="codigo" class="text-cyan-200 text-lg font-semibold block mb-1">Código</label>
            <textarea id="codigo" name="codigo"></textarea>
        </div>

        <!-- BOTÕES -->
        <div class="flex gap-3 justify-end">
            <a href="/" class="px-6 py-2 rounded-lg border border-cyan-500 text-white hover:bg-cyan-950 transition">
                Cancelar
            </a>
            <button type="submit" class="w-full md:w-auto bg-gradient-to-b from-cyan-700 to-cyan-900 text-white py-2 px-6 rounded-lg font-semibold hover:from-cyan-600 hover:to-cyan-800 transition">
                Publicar Post
            </button>
        </div>
    </form>
</main>

<script>
    // CodeMirror Editor
    var editor = CodeMirror.fromTextArea(document.getElementById('codigo'), {
        lineNumbers: true,
        mode: "javascript",
        theme: "dracula",
        indentUnit: 2,
        indentWithTabs: true,
        styleActiveLine: true,
        matchBrackets: true,
        autofocus: false,
    });

    // Visual integrado ao BugTalk
    editor.getWrapperElement().style.fontSize = '1.1rem';
    editor.getWrapperElement().style.borderRadius = '0.75rem';
    editor.getWrapperElement().style.border = '2px solid #22d3ee';
    editor.getWrapperElement().style.background = '#1e293b';
    editor.getWrapperElement().style.color = '#fff';

    // Troca automática de linguagem
    document.getElementById('language').addEventListener('change', function() {
        editor.setOption('mode', this.value);
    });

    // Preview do nome do arquivo
    function previewFileName(input) {
        const nome = input.files.length ? input.files[0].name : '';
        document.getElementById('nome-arquivo').textContent = nome;
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
