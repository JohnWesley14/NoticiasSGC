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
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">Gráficos</h1>
            <p class="mt-1 text-sm text-slate-600">Veja os gráficos da CNH Social</p>
        </div>
    </div>

    <div>
        <label for="selectGrafico" class="text-sm font-medium text-slate-700">Filtrar Gráfico</label>
        <select id="selectGrafico" class="mt-1 h-10 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-100">
            <option value="inscricoes_dia">Inscrições por Dia</option>
            <option value="inscricoes_pcd">Inscrições PCD</option>
            <option value="inscricoes_top">Top Inscrições</option>
        </select>
    </div>

    <?php if (empty($itens)) : ?>
        <p class="text-slate-500">Nenhum dado encontrado para gerar os gráficos.</p>
    <?php else : ?>
        <div style="width: 100%; max-width: 700px; margin: 20px auto;">
            <canvas id="meuGrafico"></canvas>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rawItens = <?php echo json_encode($itens ?? []); ?>;
        let meuGrafico = null; 

        const dadosGraficos = {
            inscricoes_dia: {
                titulo: 'Inscrições por Dia',
                tipo: 'line',
                labels: (rawItens.inscricoes_dia || []).map(item => item.data),
                values: (rawItens.inscricoes_dia || []).map(item => Number(item.total))
            },
            inscricoes_pcd: {
                titulo: 'Inscrições PCD',
                tipo: 'bar', 
                labels: (rawItens.inscricoes_pcd || []).map(item => item.eh_pcd == 1 ? 'PCD' : 'Não PCD'),
                values: (rawItens.inscricoes_pcd || []).map(item => Number(item.total))
            },
            inscricoes_top: {
                titulo: 'Top 5 Cidades com Mais Inscrições',
                tipo: 'bar', // Recomendo 'bar' para ranking
                labels: (rawItens.inscricoes_top || []).map(item => item.cidade),
                labels: (rawItens.inscricoes_top || []).map(item => `${item.ranking}º ${item.cidade}`),
                values: (rawItens.inscricoes_top || []).map(item => Number(item.total))
            }
        };

        function carregarGrafico(chave) {
            const config = dadosGraficos[chave];
            const ctx = document.getElementById('meuGrafico');
            console.log(rawItens);
        
            if (!ctx || !config) return;

            if (meuGrafico) {
                meuGrafico.destroy();
            }

            meuGrafico = new Chart(ctx, {
                type: config.tipo,
                data: {
                    labels: config.labels,
                    datasets: [{
                        label: config.titulo,
                        data: config.values,
                        borderWidth: 2,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        const select = document.getElementById('selectGrafico');
        select.addEventListener('change', (e) => {
            carregarGrafico(e.target.value);
        });

        carregarGrafico(select.value);
    });
</script>