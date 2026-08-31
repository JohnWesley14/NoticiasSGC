<?php

declare(strict_types=1);

namespace Src\App\Http\Requests;

use Src\Core\Request;

// TODO: adapte as regras e mensagens para o seu domínio
class GraficosRequest extends Request
{
    public function rules(): array
    {
        return [
            'id'        => ['nullable', 'string'],
            'titulo'    => ['required', 'string', 'min:3', 'max:150'],
            'descricao' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.string'   => 'O título deve ser um texto.',
            'titulo.min'      => 'O título deve ter pelo menos 3 caracteres.',
            'titulo.max'      => 'O título não pode exceder 150 caracteres.',
            'descricao.max'   => 'A descrição não pode exceder 500 caracteres.',
        ];
    }
}
