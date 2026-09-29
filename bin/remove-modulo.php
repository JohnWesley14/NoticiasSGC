#!/usr/bin/env php
<?php

$nomeModulo = $argv[1] ?? null;

if (!$nomeModulo) {
    echo "\033[31mErro: Por favor, forneça o nome do módulo a ser removido.\033[0m\n";
    echo "Uso: php bin/remove-modulo.php NomeDoModulo\n";
    exit(1);
}

$nomeClasse = ucfirst($nomeModulo);
$nomeLower  = strtolower($nomeClasse);

$diretorios = [
    'Repositories'  => __DIR__ . '/../src/App/Infrastructure/Repositories/',
    'IRepositories' => __DIR__ . '/../src/App/Infrastructure/IRepositories/',
    'Controllers'   => __DIR__ . '/../src/App/Http/Controllers/',
    'Auditoria'     => __DIR__ . '/../src/App/Services/Auditoria/',
    'Services'      => __DIR__ . '/../src/App/Services/',
    'IServices'     => __DIR__ . '/../src/App/Services/IServices/',
    'Exceptions'    => __DIR__ . '/../src/App/Http/Exceptions/' . $nomeClasse . '/',
    'Requests'      => __DIR__ . '/../src/App/Http/Requests/',
    'Views'         => __DIR__ . '/../src/views/' . $nomeClasse . '/',
    'Models'        => __DIR__ . '/../src/App/Models/',
    'Routes'        => __DIR__ . '/../src/routes/modulos/',
    'DiConfig'      => __DIR__ . '/../src/config/dependencies/modulos/',
];

$arquivos = [
    $diretorios['IRepositories'] . 'I' . $nomeClasse . 'Repository.php',
    $diretorios['Repositories']  . $nomeClasse . 'Repository.php',
    $diretorios['Controllers']   . $nomeClasse . 'Controller.php',
    $diretorios['Auditoria']     . 'Auditoria' . $nomeClasse . '.php',
    $diretorios['Services']      . $nomeClasse . 'Service.php',
    $diretorios['IServices']     . 'I' . $nomeClasse . 'Service.php',
    $diretorios['Exceptions']    . $nomeClasse . 'Exception.php',
    $diretorios['Requests']      . $nomeClasse . 'Request.php',
    $diretorios['Views']         . 'index.php',
    $diretorios['Views']         . 'criar.php',
    $diretorios['Views']         . 'editar.php',
    $diretorios['Models']        . $nomeClasse . '.php',
    $diretorios['Routes']        . $nomeLower . '.php',
    $diretorios['DiConfig']      . $nomeLower . '.php',
];

echo "\033[36mIniciando remoção do módulo '{$nomeClasse}'...\033[0m\n\n";

// 1. Apagar os arquivos gerados
foreach ($arquivos as $arquivo) {
    if (file_exists($arquivo)) {
        unlink($arquivo);
        echo "\033[32mRemovido: {$arquivo}\033[0m\n";
    }
}

// Opcional: remover os diretórios se estiverem vazios (ex: pasta de Views e Exceptions)
if (is_dir($diretorios['Views']) && count(scandir($diretorios['Views'])) === 2) rmdir($diretorios['Views']);
if (is_dir($diretorios['Exceptions']) && count(scandir($diretorios['Exceptions'])) === 2) rmdir($diretorios['Exceptions']);

// Função auxiliar para reverter injeção
function reverterInjecao(string $caminhoArquivo, string $conteudoRemover): void
{
    if (!file_exists($caminhoArquivo)) return;

    $conteudoAtual = file_get_contents($caminhoArquivo);
    
    if (strpos($conteudoAtual, $conteudoRemover) !== false) {
        // Substitui o bloco injetado (incluindo a quebra de linha) por vazio
        $novoConteudo = str_replace("\n" . $conteudoRemover, '', $conteudoAtual);
        file_put_contents($caminhoArquivo, $novoConteudo);
        echo "\033[32mSucesso: Código revertido em {$caminhoArquivo}\033[0m\n";
    } else {
        echo "\033[33mAviso: Código não encontrado em {$caminhoArquivo} para reverter.\033[0m\n";
    }
}

// 2. Reverter injeções
$sidebarPath = __DIR__ . '/../src/views/Shared/sidebar.php';
$htmlSidebar = <<<HTML
    <li>
        <a href="<?= htmlspecialchars(\$url{$nomeClasse}) ?>"
            class="<?= \$active{$nomeClasse} ? \$linkActive : \$linkInactive ?>">
            <i class="fa-solid fa-box text-base"></i>
            <span>{$nomeClasse}s</span>
        </a>
    </li>
HTML;
reverterInjecao($sidebarPath, $htmlSidebar);

$varsSidebarDefaults = <<<PHP
\$active{$nomeClasse} = \$active{$nomeClasse} ?? false;
\$url{$nomeClasse}    = \$url{$nomeClasse} ?? '';
PHP;
reverterInjecao($sidebarPath, $varsSidebarDefaults);

$sharedControllerPath = __DIR__ . '/../src/App/Http/Controllers/SharedController.php';
$varsController = <<<PHP
        'active{$nomeClasse}' => \$activePage === '{$nomeLower}',
        'url{$nomeClasse}'    => \Src\App\Utils\Url::path('/{$nomeLower}'),
PHP;
reverterInjecao($sharedControllerPath, $varsController);

echo "\n\033[36mMódulo '{$nomeClasse}' removido com sucesso!\033[0m\n";