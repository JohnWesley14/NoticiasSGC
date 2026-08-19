<?php

/**
 * @var string $voltarUrl
 * @var string $editarUrl
 * @var object $itens
 */

?>

<div class="space-y-6">
    <!-- Cabeçalho e Ações (Fora do Card) -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Voltar para a lista
            </a>
            <h1 class="mt-2 text-2xl font-semibold text-slate-900">Visualizar Imagem</h1>
        </div>

        <?php if (isset($editarUrl)) : ?>
            <a href="<?= $editarUrl . '?id=' . $itens->getId() ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-pen"></i> Editar imagem
            </a>
        <?php endif; ?>
    </div>

    <!-- Card Principal -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <div class="space-y-8">
            
            <!-- 1. Título e Status -->
            <div class="space-y-4 border-b border-slate-100 pb-6">
                <div class="flex items-center justify-between gap-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold
                        <?= $itens->getStatus() ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' ?>">
                        <span class="h-2 w-2 rounded-full <?= $itens->getStatus() ? 'bg-green-500' : 'bg-rose-500' ?>"></span>
                        <?= $itens->getStatus() ? 'Ativo' : 'Inativo' ?>
                    </span>
                   <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold bg-violet-200 text-black-200">
                    <?= $itens->getTipo() ?>
                </span>
                    <span class="text-xs font-medium text-slate-400">
                        ID: #<?= htmlspecialchars($itens->getId()) ?>
                    </span>
                </div>
                
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl break-all">
                    <?= htmlspecialchars($itens->getTitulo()) ?>
                </h2>
            </div>

            <!-- 2. Imagem (Menor e Centralizada) -->
            <?php if ($itens->getCaminho()) : ?>
                <div class="flex justify-center pt-4">
                    <img src="public<?= htmlspecialchars($itens->getCaminho()) ?>" 
                         alt="<?= htmlspecialchars($itens->getTitulo()) ?>" 
                         class="h-64 w-full max-w-lg rounded-xl border border-slate-200 object-cover shadow-sm">
                </div>
            <?php else : ?>
                <div class="mx-none flex h-[200px] w-[100%]  items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50 text-slate-400">
                    <div class="text-center">
                        <i class="fa-regular fa-image text-2xl mb-1"></i>
                        <p class="text-xs font-medium">Sem imagem</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 3. Descrição / Legenda -->
            <div class="space-y-2 pt-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Conteúdo da Imagem</span>
                <p class="text-base leading-relaxed text-slate-700 whitespace-pre-line break-all">
                    <?= $itens->getLegenda() 
                        ? nl2br(htmlspecialchars($itens->getLegenda())) 
                        : '<em class="text-slate-400">Nenhuma descrição foi informada para esta imagem.</em>' ?>
                </p>
            </div>

        </div>
    </div>
</div>