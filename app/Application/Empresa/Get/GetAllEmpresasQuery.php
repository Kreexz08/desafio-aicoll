<?php

namespace App\Application\Empresa\Get;

use App\Application\Query;

class GetAllEmpresasQuery implements Query
{
    public function __construct(
        public readonly ?string $estado = null, // Filtro opcional por estado
        public readonly ?int $page = null,    // Opcional para paginación
        public readonly ?int $perPage = null  // Opcional para paginación
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'estado' => $this->estado,
            'page' => $this->page,
            'per_page' => $this->perPage, // Nota: la clave es 'per_page', usual en requests
        ], fn($value) => $value !== null);
    }
}
