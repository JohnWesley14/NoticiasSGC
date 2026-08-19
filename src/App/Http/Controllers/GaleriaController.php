<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Http\Exceptions\Galeria\GaleriaExecptions;
use Src\App\Http\Requests\GaleriaRequest;
use Src\App\Services\IServices\IGaleriaService;
use Src\App\Utils\Toast;
use Src\App\Utils\Url;
use Src\App\Utils\View;
use Src\Core\Logger;

// TODO: renomeie para o seu domínio e ajuste as rotas/títulos
class GaleriaController extends SharedController
{
    public function __construct(
        private IGaleriaService $galeriaService
    ) {
    }

    public function index(): string
    {
        try {
            $itens = $this->galeriaService->getAll();
            $content = View::render('galeria/index', [
                'itens'      => $itens,
                'voltarUrl'  => Url::path('/home'),
                'urlCriar'   => Url::path('/galeria/criar'),
                'editarUrl'  => Url::path('/galeria/editar'),
                'deletarUrl' => Url::path('/galeria/deletar'),
                'visualizarUrl' => Url::path('/galeria/visualizar')
            ]);

            return self::getPage('GALERIA - LISTAGEM', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'galeria-page',
                'activePage'  => 'galeria',
            ]);
        } catch (GaleriaExecptions $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/home');
        }
    }

    public function criar(): string
    {
        $content = View::render('galeria/criar', [
            'voltarUrl' => Url::path('/galeria'),
            'urlSalvar' => Url::path('/galeria/salvar'),
        ]);

        return self::getPage('GALERIA - NOVO REGISTRO', $content, [
            'showSidebar' => true,
            'bodyClass'   => 'galeria-page',
            'activePage'  => 'galeria',
        ]);
    }

    public function editar(): string
    {
        $id = trim((string) filter_input(INPUT_GET, 'id'));
        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/galeria');
        }

        try {
            $item = $this->galeriaService->getById($id);

            if ($item === null) {
                Toast::error('Registro não encontrado.');
                Url::redirect('/galeria');
            }

            $content = View::render('galeria/editar', [
                'voltarUrl' => Url::path('/galeria'),
                'urlSalvar' => Url::path('/galeria/atualizar'),
                'item'      => $item,
            ]);

            return self::getPage('GALERIA - EDITAR REGISTRO', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'galeria-page',
                'activePage'  => 'galeria',
            ]);
        } catch (GaleriaExecptions $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/galeria');
        }
    }

    public function salvar(): never
    {
        try {
            $request = (new GaleriaRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->galeriaService->create($validated);
            Toast::success('Registro criado com sucesso!');
            Url::redirect('/galeria');
        } catch (GaleriaExecptions $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/galeria');
        }
    }

    public function atualizar(): void
    {
        try {
            $request = (new GaleriaRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->galeriaService->update((string) $validated['id'], $validated);
            Toast::success('Registro atualizado com sucesso!');
            Url::redirect('/galeria');
        } catch (GaleriaExecptions $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/galeria/editar?id=' . ($_POST['id'] ?? ''));
        }
    }

    public function deletar(): void
    {
        $id = trim((string) filter_input(INPUT_POST, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/galeria');
        }

        try {
            $this->galeriaService->delete($id);
            Toast::success('Registro deletado com sucesso!');
            Url::redirect('/galeria');
        } catch (GaleriaExecptions $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/galeria');
        }
    }
    public function visualizar(){
        $id = filter_input(INPUT_GET, "id");
        
           try {
            $itens = $this->galeriaService->getById($id);
           
            $content = View::render('galeria/visualizar', [
                'itens'      => $itens,
                'voltarUrl'  => Url::path('/galeria'),
                'visualizarUrl' => Url::path('/galeria/visualizar')
            ]);

            return self::getPage('GALERIA - LISTAGEM', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'galeria-page',
                'activePage'  => 'galeria',
            ]);
        } catch (GaleriaExecptions $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/home');
        }
    }
}
