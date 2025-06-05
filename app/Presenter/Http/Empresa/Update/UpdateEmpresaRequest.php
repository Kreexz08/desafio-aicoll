<?php

namespace App\Presenter\Http\Empresa\Update;

use Illuminate\Foundation\Http\FormRequest;
use App\Application\Empresa\Update\UpdateEmpresaCommand;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'string', 'max:255'],
            'direccion' => ['sometimes', 'string', 'max:500'],
            'telefono' => ['sometimes', 'string', 'digits:10'],
            'estado' => ['sometimes', 'string', 'in:Activo,Inactivo'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no puede exceder los 500 caracteres.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.digits' => 'El teléfono debe contener exactamente 10 dígitos.',
            'estado.string' => 'El estado debe ser una cadena de texto.',
            'estado.in' => 'El estado proporcionado no es válido. Debe ser "Activo" o "Inactivo".',
        ];
    }

    public function toCommand(string $nit): UpdateEmpresaCommand
    {
        return new UpdateEmpresaCommand(
            nit: $nit,
            nombre: $this->input('nombre'),
            direccion: $this->input('direccion'),
            telefono: $this->input('telefono'),
            estado: $this->input('estado')
        );
    }
}
