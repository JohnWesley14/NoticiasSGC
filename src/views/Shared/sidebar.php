<?php
// TODO: adicione aqui as entradas de menu dos módulos do seu projeto
$isAdmin        = $isAdmin ?? false;
$activeDashboard = $activeDashboard ?? false;
$activeAdmin    = $activeAdmin ?? false;
$activeExemplo  = $activeExemplo ?? false;
$activeGaleria  = $activeGaleria ?? false;
$activeConfig   = $activeConfig ?? false;

$urlDashboard = $urlDashboard ?? '';
$urlAdmin     = $urlAdmin ?? '';
$urlExemplo   = $urlExemplo ?? '';
$urlGaleria   = $urlGaleria ?? '';
$urlConfig    = $urlConfig ?? '';
$urlLogout    = $urlLogout ?? '';

$linkBase     = 'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors';
$linkActive   = $linkBase . ' bg-slate-900 text-white hover:bg-slate-800';
$linkInactive = $linkBase . ' text-slate-700 hover:bg-slate-100 hover:text-slate-900';
?>

<aside id="appSidebar" class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-slate-200 bg-white transition-transform duration-200 lg:static lg:translate-x-0">
    <div class="flex h-16 items-center justify-center gap-3 border-b border-slate-200 px-4">
        <a href="<?= htmlspecialchars($urlDashboard) ?>" class="flex items-center gap-3">
            <span class="text-sm font-bold tracking-wide text-slate-900">Meu Projeto</span>
        </a>
        <button type="button" id="closeSidebar"
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100 lg:hidden"
            aria-label="Fechar menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="flex h-[calc(100%-4rem)] flex-col justify-between overflow-y-auto p-4">
        <ul class="space-y-1">
            <li>
                <a href="<?= htmlspecialchars($urlDashboard) ?>"
                    class="<?= $activeDashboard ? $linkActive : $linkInactive ?>">
                    <i class="fa-solid fa-home text-base"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if ($isAdmin) : ?>
            <li>
                <a href="<?= htmlspecialchars($urlAdmin) ?>"
                    class="<?= $activeAdmin ? $linkActive : $linkInactive ?>">
                    <i class="fa-solid fa-shield-halved text-base"></i>
                    <span>Administração</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- TODO: substitua este item pelo(s) módulo(s) do seu projeto -->
            <li>
                <a href="<?= htmlspecialchars($urlExemplo) ?>"
                    class="<?= $activeExemplo ? $linkActive : $linkInactive ?>">
                    <i class="fa-solid fa-layer-group text-base"></i>
                    <span>Exemplo</span>
                </a>
            </li>

            <li>
                <a href="<?= htmlspecialchars($urlGaleria) ?>"
                    class="<?= $activeGaleria ? $linkActive : $linkInactive ?>">
                    <i class="fa-solid fa-layer-group text-base"></i>
                    <span>Galeria</span>
                </a>
            </li>
        </ul>

        <ul class="space-y-1 pt-4">
            <li>
                <a href="<?= htmlspecialchars($urlConfig) ?>"
                    class="<?= $activeConfig ? $linkActive : $linkInactive ?>">
                    <i class="fa-solid fa-gear text-base"></i>
                    <span>Configurações</span>
                </a>
            </li>
            <li>
                <a href="<?= htmlspecialchars($urlLogout) ?>" class="<?= $linkInactive ?>">
                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                    <span>Sair</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
