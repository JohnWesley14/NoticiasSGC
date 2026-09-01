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

    <?php else : ?>
       
        
         <div style="width: 600px; margin: 40px auto;">
        <!-- Canvas onde o gráfico será desenhado -->
        <canvas id="meuGrafico"></canvas>
         </div>

    <?php endif; ?>
</div>
<script src="public/js/galeria-filtrar.js" defer></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const itens = <?php echo json_encode($itens ?? []); ?>;
        console.log(itens);
        console.log(itens[0]);
    
        const labels = itens.map(item => (item.data));
        const dataValues = itens.map(item => Number(item.total));

        const ctx = document.getElementById('meuGrafico');
        
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels, 
                    datasets: [{
                        label: 'Inscrições',
                        data: dataValues,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>