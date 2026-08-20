<?php

/**
 * @var string $voltarUrl
 * @var string $urlSalvar
 */

?>

<div class="space-y-6">
    <div>
        <a href="<?= $voltarUrl ?>" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Nova Imagem</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" action="<?= $urlSalvar ?>" enctype="multipart/form-data">
            <?= csrf() ?>

            <div class="space-y-4">
                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-slate-700">Título <span class="text-rose-600">*</span></label>
                    <input type="text" id="titulo" name="titulo" required minlength="3" maxlength="150"
                        value="<?= old('titulo') ?>"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <?= old_error('titulo') ?>
                </div>

                <!-- Tipo -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-slate-700">Tipo</label>
                    <select id="tipo" name="tipo"
                        class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value="">Selecione um tipo...</option>
                        <option value="Esporte" <?= old('tipo') === 'Esporte' ? 'selected' : '' ?>>Esporte</option>
                        <option value="Natureza" <?= old('tipo') === 'Natureza' ? 'selected' : '' ?>>Natureza</option>
                        <option value="Automotivo" <?= old('tipo') === 'Automotivo' ? 'selected' : '' ?>>Automotivo</option>
                        <option value="Tecnologia" <?= old('tipo') === 'Tecnologia' ? 'selected' : '' ?>>Tecnologia</option>
                    </select>
                    <?= old_error('tipo') ?>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                    <select id="status" name="status" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        <option value="1" <?= old('status', '1') == '1' ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= old('status') == '0' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                    <?= old_error('status') ?>
                </div>

                <!-- Legenda -->
                <div>
                    <label for="legenda" class="block text-sm font-medium text-slate-700">Legenda</label>
                    <textarea id="legenda" name="legenda" maxlength="500" rows="3"
                        class="mt-1 block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100"><?= old('legenda') ?></textarea>
                    <?= old_error('legenda') ?>
                </div>

                <!-- Input file da imagem com preview e botão de remoção -->
                <div class="space-y-4">
                    <span class="block text-sm font-medium text-slate-700">Enviar Imagem</span>

                    <!-- Container do preview da NOVA imagem (Oculto por padrão) -->
                    <div class="hidden items-center gap-4 rounded-xl border border-blue-100 bg-blue-50/50 p-4 transition-all" id="box_preview_novo">
                        <img id="preview" src="" alt="Pré-visualização" class="h-[200px] w-[300px] rounded-xl object-cover border border-slate-200 shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-blue-800">Nova imagem selecionada</p>
                            <p class="text-xs text-blue-600 mt-0.5" id="nome_arquivo_selecionado"></p>
                        </div>
                    </div>

                    <!-- Botões: Selecionar e Excluir -->
                    <div class="flex items-center gap-3">
                        <label for="imagem" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-upload"></i> 
                            <span id="label_texto_btn">Selecionar arquivo</span>
                        </label>
                        
                        <input id="imagem" type="file" accept="image/*" name="imagem" class="hidden">
                        
                        <!-- O Botão global de remover -->
                        <button type="button" id="btn_remover_imagem" class="hidden items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                            <i class="fa-solid fa-trash"></i> Excluir imagem
                        </button>
                    </div>

                    <?= old_error('imagem') ?>
                    <p class="mt-1 text-xs text-slate-500">PNG, JPG ou JPEG (Tamanho máximo 2 MB).</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="<?= $voltarUrl ?>"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <i class="fa-solid fa-floppy-disk"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
<script src="public/js/preview-img.js" defer></script>