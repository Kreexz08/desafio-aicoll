<?php

namespace App\Presenter\Http\Empresa\Get;

use Illuminate\Foundation\Http\FormRequest;
use App\Application\Empresa\Get\GetAllEmpresasQuery;

class ListEmpresasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite la solicitud por ahora
    }

    public function rules(): array
    {
        return [
            'estado' => ['nullable', 'string', 'in:Activo,Inactivo'],
            'page' => ['nullable', 'integer', 'min:1'],
            'perPage' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'estado.in' => 'El valor para el filtro de estado no es válido. Debe ser "Activo" o "Inactivo".',
            'page.integer' => 'El número de página debe ser un entero.',
            'page.min' => 'El número de página debe ser como mínimo 1.',
            'perPage.integer' => 'El número de elementos por página debe ser un entero.',
            'perPage.min' => 'El número de elementos por página debe ser como mínimo 1.',
            'perPage.max' => 'El número de elementos por página no puede ser superior a 100.',
        ];
    }

    public function toQuery(): GetAllEmpresasQuery
    {
        return new GetAllEmpresasQuery(
            estado: $this->input('estado'),
            page: $this->input('page') ? (int)$this->input('page') : null,
            perPage: $this->input('perPage') ? (int)$this->input('perPage') : null
        );
    }
}
