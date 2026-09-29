<?php

declare(strict_types=1);

namespace Src\App\Http\Controllers;

use Src\App\Http\Exceptions\Produto\ProdutoException;
use Src\App\Http\Requests\ProdutoRequest;
use Src\App\Services\IServices\IProdutoService;
use Src\App\Utils\Toast;
use Src\App\Utils\Url;
use Src\App\Utils\View;
use Src\Core\Logger;

class ProdutoController extends SharedController
{
    public function __construct(
        private IProdutoService $produtoService
    ) {
    }

    public function index(): string
    {
        try {
            $itens = $this->produtoService->getAll();
            $content = View::render('Produto/index', [
                'itens'      => $itens,
                'voltarUrl'  => Url::path('/home'),
                'urlCriar'   => Url::path('/produto/criar'),
                'editarUrl'  => Url::path('/produto/editar'),
                'deletarUrl' => Url::path('/produto/deletar'),
            ]);

            return self::getPage('PRODUTO - LISTAGEM', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'produto-page',
                'activePage'  => 'produto',
            ]);
        } catch (ProdutoException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/home');
        }
    }

    public function criar(): string
    {
        $content = View::render('Produto/criar', [
            'voltarUrl' => Url::path('/produto'),
            'urlSalvar' => Url::path('/produto/salvar'),
        ]);

        return self::getPage('PRODUTO - NOVO REGISTRO', $content, [
            'showSidebar' => true,
            'bodyClass'   => 'produto-page',
            'activePage'  => 'produto',
        ]);
    }

    public function editar(): string
    {
        $id = trim((string) filter_input(INPUT_GET, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/produto');
        }

        try {
            $item = $this->produtoService->getById($id);

            if ($item === null) {
                Toast::error('Registro não encontrado.');
                Url::redirect('/produto');
            }

            $content = View::render('Produto/editar', [
                'voltarUrl' => Url::path('/produto'),
                'urlSalvar' => Url::path('/produto/atualizar'),
                'item'      => $item,
            ]);

            return self::getPage('PRODUTO - EDITAR REGISTRO', $content, [
                'showSidebar' => true,
                'bodyClass'   => 'produto-page',
                'activePage'  => 'produto',
            ]);
        } catch (ProdutoException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/produto');
        }
    }

    public function salvar(): never
    {
        try {
            $request = (new ProdutoRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->produtoService->create($validated);
            Toast::success('Registro criado com sucesso!');
            Url::redirect('/produto');
        } catch (ProdutoException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/produto');
        }
    }

    public function atualizar(): void
    {
        try {
            $request = (new ProdutoRequest($_POST))->redirectOnFail();
            $validated = $request->validated();

            $this->produtoService->update((string) $validated['id'], $validated);
            Toast::success('Registro atualizado com sucesso!');
            Url::redirect('/produto');
        } catch (ProdutoException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/produto/editar?id=' . ($_POST['id'] ?? ''));
        }
    }

    public function deletar(): void
    {
        $id = trim((string) filter_input(INPUT_POST, 'id'));

        if ($id === '') {
            Toast::error('ID inválido.');
            Url::redirect('/produto');
        }

        try {
            $this->produtoService->delete($id);
            Toast::success('Registro deletado com sucesso!');
            Url::redirect('/produto');
        } catch (ProdutoException $e) {
            Toast::error($e->getMessage());
            Logger::error($e->getMessage());
            Url::redirect('/produto');
        }
    }
}