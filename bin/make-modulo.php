#!/usr/bin/env php
<?php

$nomeModulo = $argv[1] ?? null;

if (!$nomeModulo) {
    echo "\033[31mErro: Por favor, forneça o nome do módulo.\033[0m\n";
    echo "Uso: php bin/make-modulo.php NomeDoModulo\n";
    exit(1);
}

// 1. Formatações de texto baseadas na entrada
$nomeClasse   = ucfirst($nomeModulo);                                          // Ex: CategoriaProduto
$nomeVariavel = lcfirst($nomeClasse);                                          // Ex: categoriaProduto
$nomeSnake    = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $nomeClasse)); // Ex: categoria_produto
$nomeLower    = strtolower($nomeClasse);                                       // Ex: categoriaproduto
$nomeUpper    = strtoupper($nomeClasse);                                       // Ex: CATEGORIAPRODUTO

// 2. Mapeamento dos diretórios de destino
$pastaStubs = __DIR__ . '/../stubs/';

$diretorios = [
    'Repositories'  => __DIR__ . '/../src/App/Infrastructure/Repositories/',
    'IRepositories' => __DIR__ . '/../src/App/Infrastructure/IRepositories/',
    'Controllers'   => __DIR__ . '/../src/App/Http/Controllers/',
    'Auditoria'     => __DIR__ . '/../src/App/Services/Auditoria/',
    'Services'      => __DIR__ . '/../src/App/Services/',
    'IServices'     => __DIR__ . '/../src/App/Services/IServices/',
    'Exceptions'    => __DIR__ . '/../src/App/Http/Exceptions/' . $nomeClasse . '/',
    'Requests'      => __DIR__ . '/../src/App/Http/Requests/',
    'Views'         => __DIR__ . '/../views/' . $nomeClasse . '/',
    'Models'        => __DIR__ . '/../src/App/Models/',
    'Routes'        => __DIR__ . '/../src/routes/modulos/',
    'DiConfig'      => __DIR__ . '/../src/config/dependencies/modulos/',
];

// 3. Garantir a criação de TODAS as pastas antes de gerar arquivos
foreach ($diretorios as $pasta) {
    if (!is_dir($pasta)) {
        mkdir($pasta, 0775, true);
    }
}

// 4. Tabela de substituição para os marcadores dos arquivos .stub
$substituicoes = [
    'Modulo'         => $nomeClasse,   // {{Modulo}}
    'ModuloVariavel' => $nomeVariavel, // {{ModuloVariavel}}
    'ModuloSnake'    => $nomeSnake,    // {{ModuloSnake}}
    'ModuloLower'    => $nomeLower,    // {{ModuloLower}}
    'ModuloUpper'    => $nomeUpper,    // {{ModuloUpper}}
];

function gerarFicheiro(string $stubPath, string $destinoPath, array $substituicoes): void
{
    if (file_exists($destinoPath)) {
        echo "\033[33mAviso: O arquivo {$destinoPath} já existe. Ignorado.\033[0m\n";
        return;
    }

    if (!file_exists($stubPath)) {
        echo "\033[31mErro: O stub {$stubPath} não foi encontrado.\033[0m\n";
        return;
    }

    $conteudo = file_get_contents($stubPath);

    foreach ($substituicoes as $chave => $valor) {
        $conteudo = str_replace('{{' . $chave . '}}', $valor, $conteudo);
    }

    file_put_contents($destinoPath, $conteudo);
    echo "\033[32mSucesso: Arquivo criado em {$destinoPath}\033[0m\n";
}

echo "\033[36mIniciando geração do módulo '{$nomeClasse}'...\033[0m\n\n";

function injetarConteudo(string $caminhoArquivo, string $hook, string $conteudoInjetar): void
{
    if (!file_exists($caminhoArquivo)) {
        echo "\033[31mErro: Arquivo para injeção não encontrado: {$caminhoArquivo}\033[0m\n";
        return;
    }

    $conteudoAtual = file_get_contents($caminhoArquivo);

    // Evita duplicar a injeção se o script for rodado duas vezes para o mesmo módulo
    if (strpos($conteudoAtual, $conteudoInjetar) !== false) {
        echo "\033[33mAviso: Conteúdo já injetado em {$caminhoArquivo}\033[0m\n";
        return;
    }

    if (strpos($conteudoAtual, $hook) === false) {
        echo "\033[31mErro: Hook '{$hook}' não encontrado em {$caminhoArquivo}\033[0m\n";
        return;
    }

    // Insere o novo conteúdo logo após o hook
    $novoConteudo = str_replace($hook, $hook . "\n" . $conteudoInjetar, $conteudoAtual);
    
    file_put_contents($caminhoArquivo, $novoConteudo);
    echo "\033[32mSucesso: Código injetado em {$caminhoArquivo}\033[0m\n";
}

// 5. Mapeamento de cada Stub para seu arquivo final
$arquivos = [
    $pastaStubs . 'IRepository.stub' => $diretorios['IRepositories'] . 'I' . $nomeClasse . 'Repository.php',
    $pastaStubs . 'Repository.stub'  => $diretorios['Repositories']  . $nomeClasse . 'Repository.php',
    $pastaStubs . 'Controller.stub'  => $diretorios['Controllers']   . $nomeClasse . 'Controller.php',
    $pastaStubs . 'Auditoria.stub'   => $diretorios['Auditoria']     . 'Auditoria' . $nomeClasse . '.php',
    $pastaStubs . 'Service.stub'     => $diretorios['Services']      . $nomeClasse . 'Service.php',
    $pastaStubs . 'IService.stub'    => $diretorios['IServices']     . 'I' . $nomeClasse . 'Service.php',
    $pastaStubs . 'Exception.stub'   => $diretorios['Exceptions']    . $nomeClasse . 'Exception.php',
    $pastaStubs . 'Request.stub'     => $diretorios['Requests']      . $nomeClasse . 'Request.php',
    $pastaStubs . 'ViewIndex.stub'   => $diretorios['Views']         . 'index.php',
    $pastaStubs . 'ViewCriar.stub'   => $diretorios['Views']         . 'criar.php',
    $pastaStubs . 'ViewEditar.stub'  => $diretorios['Views']         . 'editar.php',
    $pastaStubs . 'Model.stub'       => $diretorios['Models']        . $nomeClasse . '.php',
    $pastaStubs . 'Routes.stub'      => $diretorios['Routes']        . $nomeLower . '.php',
    $pastaStubs . 'DiConfig.stub'    => $diretorios['DiConfig']      . $nomeLower . '.php',
];

// 6. Loop de geração
foreach ($arquivos as $stub => $destino) {
    gerarFicheiro($stub, $destino, $substituicoes);
}

// --- INJEÇÃO EM ARQUIVOS EXISTENTES ---

// 1. Injetar na Sidebar
$sidebarPath = __DIR__ . '/../views/Shared/sidebar.php';
$hookSidebar = '<!-- [HOOK_SIDEBAR] -->';
$htmlSidebar = <<<HTML
    <li>
        <a href="<?= htmlspecialchars(\$url{$nomeClasse}) ?>"
            class="<?= \$active{$nomeClasse} ? \$linkActive : \$linkInactive ?>">
            <i class="fa-solid fa-box text-base"></i>
            <span>{$nomeClasse}s</span>
        </a>
    </li>
HTML;

injetarConteudo($sidebarPath, $hookSidebar, $htmlSidebar);

// 2. Injetar no SharedController (ajustado para 'src' em minúsculo, acompanhando o resto do projeto)
$sharedControllerPath = __DIR__ . '/../src/App/Http/Controllers/SharedController.php';
$hookController = '// [HOOK_SIDEBAR_VARS]';
$varsController = <<<PHP
        'active{$nomeClasse}' => \$activePage === '{$nomeLower}',
        'url{$nomeClasse}'    => \Src\App\Utils\Url::path('/{$nomeLower}'),
PHP;

injetarConteudo($sharedControllerPath, $hookController, $varsController);

echo "\n\033[36mTodos os arquivos do módulo '{$nomeClasse}' foram gerados com sucesso!\033[0m\n";