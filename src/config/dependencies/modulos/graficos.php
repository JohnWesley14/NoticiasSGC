<?php

declare(strict_types=1);

use Src\App\Http\Controllers\GraficosController;
use Src\App\Infrastructure\IRepositories\IGraficosRepository;
use Src\App\Infrastructure\Repositories\GraficosRepository;
use Src\App\Services\Auditoria\AuditoriaGraficos;
use Src\App\Services\GraficosService;
use Src\App\Services\IServices\IAuditoriaService;
use Src\App\Services\IServices\IGraficosService;
use Src\Core\Container;
use Src\Core\Database;


// =====================
// REPOSITORIES
// =====================
Container::set(IGraficosRepository::class, static function () {
    return new AuditoriaGraficos(
        new GraficosRepository(Container::get(Database::class)),
        Container::get(IAuditoriaService::class)
    );
});

// =====================
// SERVICES
// =====================
Container::set(IGraficosService::class, static function () {
    return new GraficosService(
        Container::get(IGraficosRepository::class)
    );
});

// =====================
// CONTROLLERS
// =====================
Container::set(GraficosController::class, static function () {
    return new GraficosController(
        Container::get(IGraficosService::class)
    );
});
