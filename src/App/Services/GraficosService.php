<?php

declare(strict_types=1);

namespace Src\App\Services;

use Src\App\Http\Exceptions\Graficos\GraficosException;
use Src\App\Infrastructure\IRepositories\IGraficosRepository;
use Src\App\Models\Graficos;
use Src\App\Services\IServices\IGraficosService;

class GraficosService implements IGraficosService
{
    public function __construct(
        private IGraficosRepository $repository
    ) {
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
        return $this->repository->getInscricoesPorDia();
    }
}
