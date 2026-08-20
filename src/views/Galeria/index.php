<?php

/**
 * @var array  $itens
 * @var string $urlCriar
 * @var string $editarUrl
 * @var string $deletarUrl
 * @var string $visualizarUrl
 */

?>
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">Galeria</h1>
            <p class="mt-1 text-sm text-slate-600">Gerencie os registros de Galeria</p>
        </div>
        <a href="<?= $urlCriar ?>"
            class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition-colors">
            <i class="fa-solid fa-plus"></i>
            Novo Registro
        </a>
    </div>
    <div>
        <label for="filterRole" class="text-sm font-medium text-slate-700">Filtrar</label>
        <select id="filterRole" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
            <option value=''>Todos os tipos</option>
            <option value="1">Esporte</option>
            <option value="2">Natureza</option>
            <option value="3">Automotivo</option>
            <option value="4">Tecnologia</option>
        </select>
    </div>

    <?php if (empty($itens)) : ?>
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-6 sm:p-10 text-center">
            <div class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <p class="mt-3 text-sm font-semibold text-slate-900">Nenhum registro cadastrado.</p>
            <p class="mt-1 text-sm text-slate-600">Clique em "Novo Registro" para começar.</p>
        </div>
    <?php else : ?>
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/50">
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Título</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Legenda</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Tipo</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Status</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200" id="userTableBody">
                        <?php foreach ($itens as $item) : ?>
                            <tr class="hover:bg-slate-50/50 transition-colors" data-tipo="<?= ( htmlspecialchars($item->getTipo()) )?>">
                                <td class="px-5 py-4 font-medium text-slate-900" ><?= htmlspecialchars($item->getTitulo()) ?></td>

                                <td class="px-5 py-4 text-slate-600 max-w-[150px] sm:max-w-[200px] truncate" title="<?= htmlspecialchars($item->getLegenda() ?? '') ?>">
                                    <?= htmlspecialchars($item->getLegenda() ?? '—') ?>
                                </td>

                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($item->getTipo() ?? '—') ?></td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium <?= $item->getStatus() ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' ?>">
                                        <?= $item->getStatus() ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">

                                        <!-- Botão Ver -->
                                        <a href="<?= $visualizarUrl . '?id=' . $item->getId() ?>"
                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-50 transition-colors"
                                            title="Ver detalhes da notícia">
                                            <i class="fa-solid fa-eye"></i> Ver
                                        </a>

                                        <!-- Botão Editar -->
                                        <a href="<?= $editarUrl . '?id=' . $item->getId() ?>"
                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                                            <i class="fa-solid fa-pen"></i> Editar
                                        </a>

                                        <!-- Botão Excluir -->
                                        <button type="button"
                                            class="js-btn-excluir inline-flex items-center gap-1 rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700 transition-colors"
                                            data-id="<?= htmlspecialchars($item->getId()) ?>"
                                            data-nome="<?= htmlspecialchars($item->getTitulo()) ?>"
                                            data-url="<?= $deletarUrl ?>"
                                            data-campo="id">
                                            <i class="fa-solid fa-trash"></i> Excluir
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="public/js/galeria-filtrar.js" defer></script>
