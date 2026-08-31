<?php

declare(strict_types=1);

use Src\App\Http\Controllers\GraficosController;
use Src\App\Http\Middleware\AuthMiddleware;

// TODO: renomeie as rotas para o seu domínio (ex: /produtos, /clientes)
$router->get('/graficos', [GraficosController::class, 'index'], [AuthMiddleware::class]);

