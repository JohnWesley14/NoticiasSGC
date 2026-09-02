<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Http\Exceptions\Graficos\GraficosException;
use Src\App\Infrastructure\IRepositories\IGraficosRepository;
use Src\App\Models\Graficos;
use Src\App\Services\IServices\IGraficosService;

class GraficosService implements IGraficosService
{
    private string $caminhoCache = __DIR__ . '/../../../storage/cache/';
    public function __construct(
        private IGraficosRepository $repository
    ) {
    
        if(!is_dir($this->caminhoCache)){
            mkdir($this->caminhoCache, 0775, true);
        }
    }

    private function lembrarCache(string $nomeArquivo, callable $queryBanco, int $tempoSegundos = 1800): array
    {
        $arquivo = $this->caminhoCache . $nomeArquivo . '.json';

        // Verifica se o arquivo existe E se a última modificação dele ainda está no prazo (ex: 30 minutos)
        if (file_exists($arquivo) && (time() - filemtime($arquivo) < $tempoSegundos)) {
            // Se estiver no prazo, lê o arquivo, converte de volta pra array e retorna
            $conteudo = file_get_contents($arquivo);
            return json_decode($conteudo, true) ?: [];
        }

        // Se o arquivo não existir ou passou do tempo, ele roda a função com a query do banco
        $dados = $queryBanco();

       
        file_put_contents($arquivo, json_encode($dados));

        return $dados;
    }
    

    public function create(array $data): Graficos
    {
        $agora = date('Y-m-d H:i:s');

        $novo = Graficos::fromArray([
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'status' => true,
            'created_at' => $agora,
            'updated_at' => $agora,
        ]);

        return $this->repository->create($novo);
    }

    public function update(string $id, array $data): Graficos
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw GraficosException::naoEncontrado();
        }

        $atualizado = Graficos::fromArray([
            'id' => $id,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'status' => $existente->getStatus(),
            'created_at' => $existente->getCreatedAt(),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->repository->update($atualizado);
    }

    public function delete(string $id): bool
    {
        $existente = $this->repository->getById($id);

        if ($existente === null) {
            throw GraficosException::naoEncontrado();
        }

        return $this->repository->delete($id);
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(string $id): ?Graficos
    {
        return $this->repository->getById($id);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
    public function getInscricoesPorDia(): array{
        return $this->lembrarCache('inscricoes_dia', function(){
            return $this->repository->getInscricoesPorDia();
        }, (60 * 60 * 24));
    }
    public function getInscricoesPorPcd(): array{
        return $this->lembrarCache('inscricoes_pcd', function(){
            return $this->repository->getInscricoesPorPcd();
        }, (60 * 60 * 24));
    }
    public function getInscricoesPorTop(): array{
        return $this->lembrarCache('inscricoes_top', function(){
            return $this->repository->getInscricoesPorTop();
        }, (60 * 60 * 24));
    }
    public function getInscricoesPorPercentualDia(): array{
        return $this->lembrarCache('inscricoes_percentual_dia', function(){
            return $this->repository->getInscricoesPorPercentualDia();
        }, (60 * 60 * 24));
    }

}
