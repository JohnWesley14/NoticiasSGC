#!/usr/bin/env php
<?php

$tipo = $argv[1] ?? null; // Ex: Repository, Service
$modulo = $argv[2] ?? null; // Ex: User
$metodo = $argv[3] ?? null; // Ex: findByName
$parametros = $argv[4] ?? ''; // Ex: string $name
$retorno = $argv[5] ?? ''; // Ex: ?array

if (!$tipo || !$modulo || !$metodo) {
    echo "\033[31mErro: Argumentos insuficientes.\033[0m\n";
    echo "Uso: php bin/make-method.php <Tipo> <Modulo> <Metodo> [Parametros] [Retorno]\n";
    echo "Exemplo: php bin/make-method.php Repository User findByName \"string \$name\" \"?array\"\n";
    exit(1);
}

$tipo = ucfirst($tipo);
$modulo = ucfirst($modulo);

// Caminhos padrão
$diretorios = [
    'Repository' => [
        'interface' => __DIR__ . "/../src/App/Infrastructure/IRepositories/I{$modulo}Repository.php",
        'classe'    => __DIR__ . "/../src/App/Infrastructure/Repositories/{$modulo}Repository.php",
    ],
    'Service' => [
        'interface' => __DIR__ . "/../src/App/Services/IServices/I{$modulo}Service.php",
        'classe'    => __DIR__ . "/../src/App/Services/{$modulo}Service.php",
    ]
];

if (!array_key_exists($tipo, $diretorios)) {
    echo "\033[31mErro: Tipo '{$tipo}' não suportado. Use Repository ou Service.\033[0m\n";
    exit(1);
}

$caminhoInterface = $diretorios[$tipo]['interface'];
$caminhoClasse = $diretorios[$tipo]['classe'];

// Formatar a assinatura do método
$assinaturaRetorno = $retorno ? ": {$retorno}" : "";
$assinaturaInterface = "    public function {$metodo}({$parametros}){$assinaturaRetorno};";
$assinaturaClasse = <<<PHP
    public function {$metodo}({$parametros}){$assinaturaRetorno}
    {
        // TODO: Implement {$metodo}() method.
    }
PHP;

function injetarMetodo(string $caminhoArquivo, string $codigoMetodo, string $nomeMetodo, bool $isInterface = false)
{
    if (!file_exists($caminhoArquivo)) {
        echo "\033[31mErro: Arquivo não encontrado: {$caminhoArquivo}\033[0m\n";
        return;
    }

    $conteudo = file_get_contents($caminhoArquivo);

    // Verifica se o método já existe
    if (preg_match("/function\s+{$nomeMetodo}\s*\(/i", $conteudo)) {
        echo "\033[33mAviso: Método '{$nomeMetodo}' já existe em {$caminhoArquivo}. Ignorado.\033[0m\n";
        return;
    }

    // Encontra a última chave '}' do arquivo
    $pos = strrpos($conteudo, '}');
    if ($pos === false) {
        echo "\033[31mErro: Estrutura inválida no arquivo {$caminhoArquivo}\033[0m\n";
        return;
    }

    // Insere o método antes da última chave
    $novoConteudo = substr($conteudo, 0, $pos) . "\n{$codigoMetodo}\n" . substr($conteudo, $pos);
    
    file_put_contents($caminhoArquivo, $novoConteudo);
    echo "\033[32mSucesso: Método adicionado em {$caminhoArquivo}\033[0m\n";
}

echo "\033[36mIniciando criação do método '{$metodo}' para {$modulo}{$tipo}...\033[0m\n\n";

injetarMetodo($caminhoInterface, $assinaturaInterface, $metodo, true);
injetarMetodo($caminhoClasse, $assinaturaClasse, $metodo, false);

echo "\n\033[36mProcesso concluído!\033[0m\n";
