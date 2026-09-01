<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\IRepositories;

use Src\App\Models\Graficos;

// TODO: renomeie para o seu domínio (ex: IProdutoRepository, IClienteRepository)
interface IGraficosRepository
{
    public function create(Graficos $graficos): Graficos;
    public function update(Graficos $graficos): Graficos;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Graficos;
    public function count(): int;
    public function getInscricoesPorDia(): array;
}
