<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Http\Exceptions\Graficos\GraficosException;
use Src\App\Http\Requests\GraficosRequest;
use Src\App\Services\IServices\IGraficosService;
use Src\App\Utils\Toast;
use Src\App\Utils\Url;
use Src\App\Utils\View;
use Src\Core\Logger;

// TODO: renomeie para o seu domínio e ajuste as rotas/títulos
class GraficosController extends SharedController
{
    public function __construct(
        private IGraficosService $graficosService
    ) {
    }

    public function index(): string
    {
        try {
            // $itens = $this->graficosService->getAll();
            $itens1 = $this->graficosService->getInscricoesPorDia();
            $itens = [
                'inscricoes_dia' => $this->graficosService->getInscricoesPorDia(),
                'inscricoes_pcd' => $this->graficosService->getInscricoesPorPcd(),
                'inscricoes_top' => $this->graficosService->getInscricoesPorTop(),
            ];
            $content = View::render('Graficos/index', [
                'itens'      => $itens,
                'voltarUrl'  => Url::path('/home'),
                'urlCriar'   => Url::path('/graficos/criar'),
                'editarUrl'  => Url::path('/graficos/editar'),
                'deletarUrl' => Url::path('/graficos/deletar'),
            ]);

            return self::getPage('EXEMPLO - LISTAGEM', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'graficos-page',
                'activePage'  => 'graficos',
            ]);
        } catch (GraficosException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/home');
        }
    }

    public function criar(): string
    {
        $content = View::render('Graficos/criar', [
            'voltarUrl' => Url::path('/graficos'),
            'urlSalvar' => Url::path('/graficos/salvar'),
        ]);

        return self::getPage('EXEMPLO - NOVO REGISTRO', $content, [
            'showSidebar' => true,
            'bodyClass'   => 'graficos-page',
            'activePage'  => 'graficos',
        ]);
    }

    public function editar(): string
    {
        $id = trim((string) filter_input(INPUT_GET, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/graficos');
        }

        try {
            $item = $this->graficosService->getById($id);

            if ($item === null) {
                Toast::error('Registro não encontrado.');
                Url::redirect('/graficos');
            }

            $content = View::render('Graficos/editar', [
                'voltarUrl' => Url::path('/graficos'),
                'urlSalvar' => Url::path('/graficos/atualizar'),
                'item'      => $item,
            ]);

            return self::getPage('EXEMPLO - EDITAR REGISTRO', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'graficos-page',
                'activePage'  => 'graficos',
            ]);
        } catch (GraficosException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/graficos');
        }
    }

    public function salvar(): never
    {
        try {
            $request = (new GraficosRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->graficosService->create($validated);
            Toast::success('Registro criado com sucesso!');
            Url::redirect('/graficos');
        } catch (GraficosException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/graficos');
        }
    }

    public function atualizar(): void
    {
        try {
            $request = (new GraficosRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->graficosService->update((string) $validated['id'], $validated);
            Toast::success('Registro atualizado com sucesso!');
            Url::redirect('/graficos');
        } catch (GraficosException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/graficos/editar?id=' . ($_POST['id'] ?? ''));
        }
    }

    public function deletar(): void
    {
        $id = trim((string) filter_input(INPUT_POST, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/graficos');
        }

        try {
            $this->graficosService->delete($id);
            Toast::success('Registro deletado com sucesso!');
            Url::redirect('/graficos');
        } catch (GraficosException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/graficos');
        }
    }
}
