<?php

declare(strict_types=1);

namespace Src\App\Services\Auditoria;

use Override;
use Src\App\Infrastructure\IRepositories\IGraficosRepository;
use Src\App\Models\Graficos;
use Src\App\Services\IServices\IAuditoriaService;

// TODO: renomeie para o seu domínio (ex: AuditoriaProduto, AuditoriaCliente)
class AuditoriaGraficos implements IGraficosRepository
{
    public function __construct(
        private IGraficosRepository $repository,
        private IAuditoriaService $auditoriaService
    ) {
    }

    public function create(Graficos $graficos): Graficos
    {
        $criado = $this->repository->create($graficos);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Graficos',
            'create',
            $criado->getId(),
            ['titulo' => $criado->getTitulo(), 'descricao' => $criado->getDescricao()]
        );
        return $criado;
    }

    public function update(Graficos $graficos): Graficos
    {
        $antes = $this->repository->getById($graficos->getId());
        $atualizado = $this->repository->update($graficos);
        $this->auditoriaService->registrar(
            $_SESSION['user_id'] ?? null,
            'Graficos',
            'update',
            $atualizado->getId(),
            [
                'antes'  => ['titulo' => $antes?->getTitulo(), 'descricao' => $antes?->getDescricao()],
                'depois' => ['titulo' => $atualizado->getTitulo(), 'descricao' => $atualizado->getDescricao()],
            ]
        );
        return $atualizado;
    }

    public function delete(string $id): bool
    {
        $deleted = $this->repository->delete($id);
        if ($deleted) {
            $this->auditoriaService->registrar(
                $_SESSION['user_id'] ?? null,
                'Graficos',
                'delete',
                $id,
                ['id' => 'Graficos com id ' . $id . ' deletado']
            );
        }
        return $deleted;
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
    public function getInscricoesPorDia(): array
    {
        return $this->repository->getInscricoesPorDia();
    }
}