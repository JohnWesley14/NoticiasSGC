#!/usr/bin/env php
<?php

$nomeModulo =$argv[1] ?? null;

if (!$nomeModulo) {
    echo "\033[31mErro: Por favor, forneça o nome do módulo.\033[0m\n";
    echo "Uso: php bin/make-modulo.php NomeDoModulo\n";
    exit(1);
}

// 1. Formatações de texto baseadas na entrada
// Formatações de texto baseadas na entrada
$nomeClasse   = ucfirst($nomeModulo);
$nomeVariavel = lcfirst($nomeClasse);
$nomeSnake    = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $nomeClasse));
$nomeUpper    = strtoupper($nomeClasse);
$nomeLower    = strtolower($nomeClasse);                                

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
    'Views'         => __DIR__ . '/../src/views/' . $nomeClasse . '/',
    'Models'        => __DIR__ . '/../src/App/Models/',
    'Routes'        => __DIR__ . '/../src/routes/modulos/',
    'DiConfig'      => __DIR__ . '/../src/config/dependencies/modulos/',
];

// 3. Garantir a criação de TODAS as pastas antes de gerar arquivos
foreach ($diretorios as$pasta) {
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

function gerarFicheiro(string $stubPath, string $destinoPath, array$substituicoes): void
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

    foreach ($substituicoes as $chave =>$valor) {
        $conteudo = str_replace('{{' .$chave . '}}', $valor,$conteudo);
    }

    file_put_contents($destinoPath,$conteudo);
    echo "\033[32mSucesso: Arquivo criado em {$destinoPath}\033[0m\n";
}

echo "\033[36mIniciando geração do módulo '{$nomeClasse}'...\033[0m\n\n";

function injetarConteudo(string $caminhoArquivo, string $hook, string$conteudoInjetar): void
{
    if (!file_exists($caminhoArquivo)) {
        echo "\033[31mErro: Arquivo para injeção não encontrado: {$caminhoArquivo}\033[0m\n";
        return;
    }

    $conteudoAtual = file_get_contents($caminhoArquivo);

    // Evita duplicar a injeção se o script for rodado duas vezes para o mesmo módulo
    if (strpos($conteudoAtual,$conteudoInjetar) !== false) {
        echo "\033[33mAviso: Conteúdo já injetado em {$caminhoArquivo}\033[0m\n";
        return;
    }

    if (strpos($conteudoAtual,$hook) === false) {
        echo "\033[31mErro: Hook '{$hook}' não encontrado em {$caminhoArquivo}\033[0m\n";
        return;
    }

    // Insere o novo conteúdo logo após o hook
    $novoConteudo = str_replace($hook,$hook . "\n" . $conteudoInjetar,$conteudoAtual);
    
    file_put_contents($caminhoArquivo,$novoConteudo);
    echo "\033[32mSucesso: Código injetado em {$caminhoArquivo}\033[0m\n";
}

// 5. Mapeamento de cada Stub para seu arquivo final
$arquivos = [$pastaStubs . 'IRepository.stub' => $diretorios['IRepositories'] . 'I' .$nomeClasse . 'Repository.php',
    $pastaStubs . 'Repository.stub'  =>$diretorios['Repositories']  . $nomeClasse . 'Repository.php',$pastaStubs . 'Controller.stub'  => $diretorios['Controllers']   .$nomeClasse . 'Controller.php',
    $pastaStubs . 'Auditoria.stub'   =>$diretorios['Auditoria']     . 'Auditoria' . $nomeClasse . '.php',$pastaStubs . 'Service.stub'     => $diretorios['Services']      .$nomeClasse . 'Service.php',
    $pastaStubs . 'IService.stub'    =>$diretorios['IServices']     . 'I' . $nomeClasse . 'Service.php',$pastaStubs . 'Exception.stub'   => $diretorios['Exceptions']    .$nomeClasse . 'Exception.php',
    $pastaStubs . 'Request.stub'     =>$diretorios['Requests']      . $nomeClasse . 'Request.php',$pastaStubs . 'ViewIndex.stub'   => $diretorios['Views']         . 'index.php',$pastaStubs . 'ViewCriar.stub'   => $diretorios['Views']         . 'criar.php',$pastaStubs . 'ViewEditar.stub'  => $diretorios['Views']         . 'editar.php',$pastaStubs . 'Model.stub'       => $diretorios['Models']        .$nomeClasse . '.php',
    $pastaStubs . 'Routes.stub'      =>$diretorios['Routes']        . $nomeLower . '.php',$pastaStubs . 'DiConfig.stub'    => $diretorios['DiConfig']      .$nomeLower . '.php',
];

// 6. Loop de geração
foreach ($arquivos as $stub =>$destino) {
    gerarFicheiro($stub, $destino,$substituicoes);
}

// --- INJEÇÃO EM ARQUIVOS EXISTENTES ---

// 1. Injetar na Sidebar
$sidebarPath = __DIR__ . '/../src/views/Shared/sidebar.php';
$hookSidebar = '<!-- [HOOK_SIDEBAR] -->';$htmlSidebar = <<<HTML
    <li>
        <a href="<?= htmlspecialchars(\$url{$nomeClasse}) ?>"
            class="<?= \$active{$nomeClasse} ? \$linkActive : \$linkInactive ?>">
            <i class="fa-solid fa-box text-base"></i>
            <span>{$nomeClasse}s</span>
        </a>
    </li>
HTML;

injetarConteudo($sidebarPath, $hookSidebar,$htmlSidebar);

// 2. Injetar no SharedController
$sharedControllerPath = __DIR__ . '/../src/App/Http/Controllers/SharedController.php';
$hookController = '// [HOOK_SIDEBAR_VARS]';$varsController = <<<PHP
        'active{$nomeClasse}' => \$activePage === '{$nomeLower}',
        'url{$nomeClasse}'    => \Src\App\Utils\Url::path('/{$nomeLower}'),
PHP;

injetarConteudo($sharedControllerPath, $hookController,$varsController);

// 3. Injetar Variáveis Padrão na Sidebar
$hookSidebarDefaults = '// [HOOK_SIDEBAR_DEFAULTS]';$varsSidebarDefaults = <<<PHP
\$active{$nomeClasse} = \$active{$nomeClasse} ?? false;
\$url{$nomeClasse}    = \$url{$nomeClasse} ?? '';
PHP;

injetarConteudo($sidebarPath, $hookSidebarDefaults,$varsSidebarDefaults);
// --- CRIAÇÃO DIRETA DA TABELA NO BANCO DE DADOS ---
echo "\n\033[36m--- Configuração do Banco de Dados ---\033[0m\n";

// 1. Parser simples para ler o arquivo .env da raiz do projeto
function lerArquivoEnv(string $caminhoEnv): array
{
    $env = [];
    if (!file_exists($caminhoEnv)) {
        return $env;
    }

    $linhas = file($caminhoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as$linha) {
        $linha = trim($linha);
        // Ignora comentários e linhas vazias
        if ($linha === '' || strpos($linha, '#') === 0) {
            continue;
        }

        if (strpos($linha, '=') !== false) {
            list($chave, $valor) = explode('=',$linha, 2);
            $chave = trim($chave);
            $valor = trim($valor);

            // Remove aspas caso existam (ex: DB_PASS="123456")
            $valor = trim($valor, '"\'');
            $env[$chave] =$valor;
        }
    }
    return $env;
}

// 2. Carrega as variáveis do .env
$envPath = __DIR__ . '/../.env';
$env = lerArquivoEnv($envPath);

// Prioridade 1: Argumentos da linha de comando | Prioridade 2: Arquivo .env | Prioridade 3: Padrão local
$dbHost = $argv[3] ?? $env['DB_HOST'] ?? '127.0.0.1';
$dbPort =$env['DB_PORT'] ?? '3306';
$dbName =$argv[2] ?? $env['DB_NAME'] ?? '';$dbUser = $env['DB_USER'] ?? 'root';$dbPass = $argv[4] ?? $env['DB_PASS'] ?? '';

function executarCriacaoTabela(string $nomeSnake, string$dbHost, string $dbPort, string$dbName, string $dbUser, string$dbPass): void
{
    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn,$dbUser, $dbPass);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "CREATE TABLE IF NOT EXISTS `tb_{$nomeSnake}` (
          `id` VARCHAR(36) NOT NULL,
          `titulo` VARCHAR(255) NOT NULL,
          `descricao` TEXT DEFAULT NULL,
          `status` TINYINT(1) DEFAULT 1,
          `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
          `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $pdo->exec($sql);
        echo "\033[32mSucesso: Tabela `tb_{$nomeSnake}` criada no banco '{$dbName}' ({$dbHost}:{$dbPort})!\033[0m\n";

    } catch (PDOException $e) {
        echo "\033[31mErro de conexão/SQL:\033[0m " . $e->getMessage() . "\n";
    }
}

// 3. Tomada de decisão automática
if (!empty($dbName)) {
    echo "Conectando usando configurações do \033[32m.env\033[0m (Banco: \033[33m{$dbName}\033[0m)...\n";
    executarCriacaoTabela($nomeSnake,$dbHost, $dbPort,$dbName, $dbUser,$dbPass);
} else {
    // Se o .env não existir ou DB_NAME estiver vazio, tenta modo interativo
    $isWindows  = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';$streamPath = $isWindows ? 'CONIN$' : 'php://stdin';
    $streamStdin = @fopen($streamPath, 'r');

    if (!$streamStdin) {$streamStdin = fopen('php://stdin', 'r');
    }

    echo "\033[33mAviso: DB_NAME não encontrado no .env.\033[0m\n";
    
    echo "👉 Digite o Nome do Banco de Dados: ";
    $linhaInput = fgets($streamStdin);$dbNameInput = $linhaInput !== false ? trim($linhaInput) : '';

    if (!empty($dbNameInput)) {
        executarCriacaoTabela($nomeSnake,$dbHost, $dbPort,$dbNameInput, $dbUser,$dbPass);
    } else {
        echo "\033[31mCriação de tabela ignorada: Nenhum banco informado.\033[0m\n";
    }

    if (is_resource($streamStdin)) {
        fclose($streamStdin);
    }
}

echo "\n\033[36mTodos os arquivos do módulo '{$nomeClasse}' foram processados com sucesso!\033[0m\n";