document.addEventListener('DOMContentLoaded', function() {
    const inputImagem = document.getElementById('imagem');
    const boxImagemAtual = document.getElementById('box_imagem_atual'); 
    const boxPreviewNovo = document.getElementById('box_preview_novo'); 
    const preview = document.getElementById('preview'); 
    const nomeArquivoSelecionado = document.getElementById('nome_arquivo_selecionado');
    const labelTextoBtn = document.getElementById('label_texto_btn');
    
    const btnRemover = document.getElementById('btn_remover_imagem');
    const inputRemover = document.getElementById('input_remover_imagem');

    // 1. Ação do Botão "Excluir imagem" (Serve para a nova e para a antiga!)
    if (btnRemover) {
        btnRemover.addEventListener('click', function() {
            // Avisa o BD para apagar
            if (inputRemover) inputRemover.value = "1"; 
            
            // Força a remoção do flex e aplica hidden na IMAGEM ANTIGA
            if (boxImagemAtual) {
                boxImagemAtual.classList.remove('flex');
                boxImagemAtual.classList.add('hidden');
            }
            
            // Força a remoção do flex e aplica hidden no PREVIEW NOVO
            if (boxPreviewNovo) {
                boxPreviewNovo.classList.remove('flex');
                boxPreviewNovo.classList.add('hidden');
            }
            
            // Limpa o arquivo recém escolhido (se houver)
            if (inputImagem) inputImagem.value = ""; 
            
            // Esvazia o src para evitar ícone de imagem quebrada
            if (preview) preview.src = ""; 
            
            // Reseta os textos e esconde o botão de remover
            if (labelTextoBtn) labelTextoBtn.textContent = 'Selecionar arquivo';
            this.classList.remove('inline-flex');
            this.classList.add('hidden');
        });
    }

    // 2. Ação ao selecionar um NOVO arquivo do computador
    if (inputImagem) {
        inputImagem.addEventListener('change', function (event) {
            const arquivo = event.target.files[0];

            if (arquivo) {
                // Monta o preview da nova foto
                const leitor = new FileReader();
                leitor.onload = function (e) {
                    if (preview) preview.src = e.target.result;
                }
                leitor.readAsDataURL(arquivo);

                // Esconde o card da foto antiga
                if (boxImagemAtual) {
                    boxImagemAtual.classList.remove('flex');
                    boxImagemAtual.classList.add('hidden');
                }
                
                // Mostra o card do preview novo adicionando o flex de volta
                if (boxPreviewNovo) {
                    boxPreviewNovo.classList.remove('hidden');
                    boxPreviewNovo.classList.add('flex');
                }
                
                if (nomeArquivoSelecionado) nomeArquivoSelecionado.textContent = arquivo.name;

                // Garante que o PHP NÃO apague a foto antiga antes da nova chegar
                if (inputRemover) inputRemover.value = "0"; 
                if (labelTextoBtn) labelTextoBtn.textContent = 'Trocar imagem';

                // Garante que o botão de excluir apareça
                if (btnRemover) {
                    btnRemover.classList.remove('hidden');
                    btnRemover.classList.add('inline-flex');
                }

            } else {
                // Se o usuário abrir a janela do PC mas clicar em "Cancelar", 
                // o navegador esvazia a foto. Escondemos o preview novo removendo o flex.
                if (boxPreviewNovo) {
                    boxPreviewNovo.classList.remove('flex');
                    boxPreviewNovo.classList.add('hidden');
                }
                
                // Se a pessoa NÃO clicou em remover a antiga antes, ela volta a aparecer (trazendo o flex).
                if (inputRemover && inputRemover.value === "0" && boxImagemAtual) {
                    boxImagemAtual.classList.remove('hidden');
                    boxImagemAtual.classList.add('flex');
                    if (labelTextoBtn) labelTextoBtn.textContent = 'Trocar imagem';
                }
            }
        });
    }
});