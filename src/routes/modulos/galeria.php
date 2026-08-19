<?php

declare(strict_types=1);

use Src\App\Http\Controllers\GaleriaController;
use Src\App\Http\Middleware\AuthMiddleware;

// TODO: renomeie as rotas para o seu domínio (ex: /produtos, /clientes)
$router->get('/galeria', [GaleriaController::class, 'index'], [AuthMiddleware::class]);
$router->get('/galeria/criar', [GaleriaController::class, 'criar'], [AuthMiddleware::class]);
$router->get('/galeria/editar', [GaleriaController::class, 'editar'], [AuthMiddleware::class]);
$router->get('/galeria/visualizar', [GaleriaController::class, 'visualizar'], [AuthMiddleware::class]);
$router->get('/galeria/visualizar/{id}', [GaleriaController::class, 'visualizar'], [AuthMiddleware::class]);
$router->post('/galeria/salvar', [GaleriaController::class, 'salvar'], [AuthMiddleware::class]);
$router->post('/galeria/atualizar', [GaleriaController::class, 'atualizar'], [AuthMiddleware::class]);
$router->post('/galeria/deletar', [GaleriaController::class, 'deletar'], [AuthMiddleware::class]);
