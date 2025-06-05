<?php

namespace App\Application\Empresa\Create;

use App\Application\Command;

class CreateEmpresaCommand implements Command
{
    public function __construct(
        public readonly string $nit,
        public readonly string $nombre,
        public readonly string $direccion,
        public readonly string $telefono,
        public readonly string $estado = 'Activo'
    ) {}

    public function toArray(): array
    {
        return [
            'nit' => $this->nit,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'estado' => $this->estado,
        ];
    }
}
