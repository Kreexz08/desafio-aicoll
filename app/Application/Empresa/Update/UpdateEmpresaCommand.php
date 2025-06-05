<?php

namespace App\Application\Empresa\Update;

use App\Application\Command;

class UpdateEmpresaCommand implements Command
{
    public function __construct(
        public readonly string $nit,
        public readonly ?string $nombre = null,
        public readonly ?string $direccion = null,
        public readonly ?string $telefono = null,
        public readonly ?string $estado = null
    ) {}

    public function toArray(): array
    {
        // Filtra los valores nulos para que solo se incluyan los datos a actualizar
        return array_filter([
            'nit' => $this->nit,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'estado' => $this->estado,
        ], fn($value) => $value !== null);
    }

    public function hasDataToUpdate(): bool
    {
        return $this->nombre !== null ||
               $this->direccion !== null ||
               $this->telefono !== null ||
               $this->estado !== null;
    }
}
