<?php

declare(strict_types=1);

namespace Src\App\Http\Requests;

use Src\Core\Request;

// TODO: adapte as regras e mensagens para o seu domínio
class GaleriaRequest extends Request
{
    public function rules(): array
    {
        return [
            'id'        => ['nullable', 'string'],
            'titulo'    => ['required', 'string', 'min:3', 'max:150'],
            'legenda' => ['nullable', 'string', 'max:500'],
            'status'    => ['required', 'boolean'],
            'tipo' => ['nullable', 'string', 'in:Natureza,Esporte,Automotivo,Tecnologia'],
            'caminho' => ['nullable', 'fileType:jpg,jpeg,png,webp', 'fileSize:2048' ],
            'remover_imagem' => ['nullable', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.string'   => 'O título deve ser um texto.',
            'titulo.min'      => 'O título deve ter pelo menos 3 caracteres.',
            'titulo.max'      => 'O título não pode exceder 150 caracteres.',
            'legenda.max'      => 'A descrição não pode exceder 500 caracteres.',
            'caminho.fileSize' => 'O tamanho do arquivo não pode exceder 2 MB',
            'caminho.fileType' => 'O tipo do arquivo deve ser: png, jpg, jpeg ou wbep.',
            'tipo.in'          => 'Selecione um dos tipos disponíveis'
        ];
    }
}
