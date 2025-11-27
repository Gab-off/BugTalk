<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<body class="admin-page-bg">
<!--TODO: consertar o header-->
<!-- Container principal da área admin -->
<div class="min-h-screen bg-gradient-to-b from-cyan-950 to-cyan-900 py-10 px-4 flex flex-col items-center">
    <div class="w-full max-w-5xl">
        <div class="flex items-center gap-3 mb-8">
            <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6m-6 0v-2a4 4 0 013-3.87M6 8a4 4 0 118 0a4 4 0 01-8 0zm13 4a4 4 0 00-3-3.87"/>
            </svg>
            <h1 class="text-3xl font-bold text-white">Administração • Usuários</h1>
        </div>

        <div class="bg-cyan-950 rounded-lg shadow p-4 overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                <tr class="text-cyan-200 border-b border-cyan-900">
                    <th class="py-2 px-4 text-left">Usuário</th>
                    <th class="py-2 px-4 text-left">Tipo</th>
                    <th class="py-2 px-4 text-center">Posts</th>
                    <th class="py-2 px-4 text-center">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr class="border-b border-cyan-900 hover:bg-cyan-800/30 transition">
                        <td class="py-3 px-4 flex items-center gap-2">
                            <span class="inline-block w-8 h-8 rounded-full bg-cyan-800 text-white flex items-center justify-center text-lg font-bold">
                                <?= strtoupper(substr($usuario['nome'] ?? '?', 0, 1)) ?>
                            </span>
                            <span class="font-semibold text-white"><?= htmlspecialchars($usuario['nome'] ?? '') ?></span>
                            <span class="ml-2 text-xs px-2 py-1 rounded bg-cyan-700 text-cyan-100"><?= htmlspecialchars($usuario['email'] ?? '') ?></span>
                        </td>
                        <td class="py-3 px-4">
                            <?php if (!empty($usuario['isAdmin'] ?? false)): ?>
                                <span class="px-2 py-1 bg-emerald-700 rounded text-emerald-100 text-xs">Administrador</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-cyan-800 rounded text-cyan-100 text-xs">Comum</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-cyan-200 text-center"><?= $usuario['postCount'] ?? 0 ?></td>
                        <td class="py-3 px-4 flex gap-2 justify-center">
                            <!-- Botão: Ver posts -->
                            <a href="/admin/usuario?id=<?= $usuario['id'] ?? '' ?>"
                               class="px-3 py-1 rounded-md bg-cyan-800 text-cyan-100 hover:bg-emerald-700 transition">
                                Ver posts
                            </a>

                            <!-- Botão: Editar -->
                            <a href="/admin/editar?id=<?= $usuario['id'] ?? '' ?>"
                               class="px-3 py-1 rounded-md bg-cyan-700 text-cyan-100 hover:bg-cyan-500 transition">
                                Editar
                            </a>

                            <!-- Banir/desbanir/timeout -->
                            <?php if (!empty($usuario['isBanned'] ?? false)): ?>
                                <!-- Usuário está banido -->
                                <span class="px-2 py-1 bg-rose-700 text-rose-100 rounded text-xs">Banido</span>
                                <a href="/admin/pardon?id=<?= $usuario['id'] ?? '' ?>"
                                   class="px-3 py-1 rounded-md bg-emerald-700 text-cyan-50 hover:bg-emerald-900 transition">Desbanir</a>
                            <?php else: ?>
                                <!-- Usuário não está banido -->
                                <a href="/admin/usuario/ban?id=<?= $usuario['id'] ?? '' ?>"
                                   class="px-3 py-1 rounded-md bg-rose-700 text-rose-50 hover:bg-rose-500 transition">Banir</a>
                            <?php endif; ?>

                            <!-- Timeout/desfazer timeout -->
                            <?php if (!empty($usuario['timeoutUntil'])): ?>
                                <span class="px-2 py-1 bg-yellow-600 text-yellow-100 rounded text-xs">
            Timeout até <?= date('d/m/Y H:i', strtotime($usuario['timeoutUntil'])) ?>
        </span>
                                <a href="/admin/desfazer_timeout?id=<?= $usuario['id'] ?? '' ?>"
                                   class="px-3 py-1 rounded-md bg-cyan-900 text-yellow-100 hover:bg-cyan-800 transition">Desfazer
                                    Timeout</a>
                            <?php else: ?>
                                <a href="/admin/timeout?id=<?= $usuario['id'] ?? '' ?>"
                                   class="px-3 py-1 rounded-md bg-yellow-700 text-yellow-50 hover:bg-yellow-900 transition">Timeout</a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
