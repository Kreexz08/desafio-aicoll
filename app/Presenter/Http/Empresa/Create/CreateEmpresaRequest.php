<?php

namespace App\Presenter\Http\Empresa\Create;

use Illuminate\Foundation\Http\FormRequest;
use App\Application\Empresa\Create\CreateEmpresaCommand;

class CreateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Aquí iría la lógica de autorización si fuera necesaria.
        // Devolver true permite la solicitud por ahora.
        return true;
    }

    public function rules(): array
    {
        return [
            'nit' => 'required|string|max:11|regex:/^\d{9}-\d{1}$/',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'telefono' => 'required|string|digits:10',
            'estado' => 'nullable|string|in:Activo,Inactivo',
        ];
    }

    public function messages(): array
    {
        // Mensajes de error personalizados
        return [
            'nit.required' => 'El NIT es obligatorio.',
            'nit.max' => 'El NIT debe tener exactamente 11 caracteres (formato XXXXXXXXX-Y).',
            'nit.regex' => 'El NIT no tiene el formato colombiano válido (XXXXXXXXX-Y).',
            'nombre.required' => 'El nombre de la empresa es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe contener exactamente 10 dígitos.',
            'estado.in' => 'El estado proporcionado no es válido. Debe ser "Activo" o "Inactivo".',
        ];
    }

    public function toCommand(): CreateEmpresaCommand
    {
        return new CreateEmpresaCommand(
            nit: $this->input('nit'),
            nombre: $this->input('nombre'),
            direccion: $this->input('direccion'),
            telefono: $this->input('telefono'),
            estado: $this->input('estado', 'Activo')
        );
    }
}
