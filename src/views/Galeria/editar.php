<?php

/**
 * @var string  $voltarUrl
 * @var string  $urlSalvar
 * @var \Src\App\Models\Galeria $item
 */

$userIsAtivo = $item->getStatus();
?>

<div class="space-y-6">
    <div>
        <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Editar Registro</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" action="<?= $urlSalvar ?>" enctype="multipart/form-data">
            <?= csrf() ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($item->getId()) ?>">

            <div class="space-y-4">
                <!-- ------------------------------- -->
                <!-- Input Título  -->
                <!-- ------------------------------- -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-slate-700">Título <span class="text-rose-600">*</span></label>
                    <input type="text" id="titulo" name="titulo" required minlength="3" maxlength="150"
                        value="<?= htmlspecialchars(old('titulo') ?: $item->getTitulo()) ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('titulo') ?>
                </div>

                <!-- ------------------------------- -->
                <!-- Input Tipo  -->
                <!-- ------------------------------- -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-slate-700">Tipo</label>
                    <?php $tipoAtual = old('tipo') ?: $item->getTipo(); ?>
                    <select id="tipo" name="tipo"
                        class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value="Esporte" <?= $tipoAtual === "Esporte" ? "selected" : "" ?>>Esporte</option>
                        <option value="Natureza" <?= $tipoAtual === "Natureza" ? "selected" : "" ?>>Natureza</option>
                        <option value="Automotivo" <?= $tipoAtual === "Automotivo" ? "selected" : "" ?>>Automotivo</option>
                        <option value="Tecnologia" <?= $tipoAtual === "Tecnologia" ? "selected" : "" ?>>Tecnologia</option>
                    </select>
                    <?= old_error('tipo') ?>
                </div>

                <!-- ------------------------------- -->
                <!-- Input Status  -->
                <!-- ------------------------------- -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                    <?php $statusAtual = old('status') !== null ? old('status') : $userIsAtivo; ?>
                    <select id="status" name="status" class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value="0" <?= $statusAtual == 0 ? 'selected' : '' ?>>Inativo</option>
                        <option value="1" <?= $statusAtual == 1 ? 'selected' : '' ?>>Ativo</option>
                    </select>
                    <?= old_error('status') ?>
                </div>

                <!-- ------------------------------- -->
                <!-- Input Legenda  -->
                <!-- ------------------------------- -->
                <div>
                    <label for="legenda" class="block text-sm font-medium text-slate-700">Legenda</label>
                    <textarea id="legenda" name="legenda" maxlength="500" rows="3"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"><?= htmlspecialchars(old('legenda') ?: ($item->getLegenda() ?? '')) ?></textarea>
                    <?= old_error('legenda') ?>
                </div>

                <!-- ------------------------------- -->
                <!-- Upload e Preview da Imagem  -->
                <!-- ------------------------------- -->
                <div class="space-y-4 pt-2">
                    <span class="block text-sm font-medium text-slate-700">Imagem da Capa</span>

                    <!-- Input oculto que avisa o PHP se é para excluir a imagem no BD -->
                    <input type="hidden" id="input_remover_imagem" name="remover_imagem" value="0">

                    <!-- Container da imagem atual (Oculto se não houver) -->
                    <?php if ($item->getCaminho()): ?>
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 transition-all w-full" id="box_imagem_atual">
                            <img src="public<?= htmlspecialchars($item->getCaminho()) ?>" alt="Imagem atual" class="h-[300px] w-full sm:w-[200px] shrink-0 rounded-xl object-cover border border-slate-200 shadow-sm">
                            <div class="w-full text-center sm:text-left">
                                <p class="text-sm font-semibold text-slate-700">Imagem atual salva</p>
                                <p class="text-xs text-slate-500 mt-0.5 break-all">Esta é a imagem atual vinculada a este registro.</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Container do preview da NOVA imagem (Oculto por padrão) -->
                    <div class="hidden flex-col sm:flex-row items-center sm:items-start gap-4 rounded-xl border border-blue-100 bg-blue-50/50 p-4 transition-all w-full" id="box_preview_novo">
                        <img id="preview" src="" alt="Pré-visualização" class="h-[200px] w-full sm:w-[300px] shrink-0 rounded-xl object-cover border border-slate-200 shadow-sm">
                        <div class="w-full text-center sm:text-left">
                            <p class="text-sm font-semibold text-blue-800">Nova imagem selecionada</p>
                            <p class="text-xs text-blue-600 mt-0.5 break-all" id="nome_arquivo_selecionado"></p>
                        </div>
                    </div>

                    <!-- Botões: Selecionar e Excluir -->
                    <div class="flex flex-wrap items-center gap-3">
                        <label for="imagem" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-upload"></i> 
                            <span id="label_texto_btn"><?= $item->getCaminho() ? 'Trocar imagem' : 'Selecionar arquivo' ?></span>
                        </label>
                        
                        <input id="imagem" type="file" accept="image/jpeg, image/png" name="imagem" class="hidden">
                        
                        <!-- O Botão global de remover -->
                        <button type="button" id="btn_remover_imagem" class="<?= $item->getCaminho() ? 'inline-flex' : 'hidden' ?> items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                            <i class="fa-solid fa-trash"></i> Excluir imagem
                        </button>
                    </div>

                    <?= old_error('imagem') ?>
                    <p class="mt-1 text-xs text-slate-500">PNG, JPG ou JPEG (Tamanho máximo 2 MB).</p>
                </div>
            </div>

            <!-- ------------------------------- -->
            <!-- Botões finais  -->
            <!-- ------------------------------- -->
            <div class="mt-8 flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <i class="fa-solid fa-floppy-disk"></i> Atualizar
                </button>
            </div>
        </form>
    </div>
</div>
<script src="public/js/preview-img.js" defer></script>