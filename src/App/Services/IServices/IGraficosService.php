<?php

declare(strict_types=1);

namespace Src\App\Services\IServices;

use Src\App\Models\Graficos;

interface IGraficosService
{
    public function create(array $data): Graficos;
    public function update(string $id, array $data): Graficos;
    public function delete(string $id): bool;
    public function getAll(): array;
    public function getById(string $id): ?Graficos;
    public function count(): int;
    public function getInscricoesPorDia(): array;
    public function getInscricoesPorPcd(): array;
    public function getInscricoesPorTop(): array;

}
