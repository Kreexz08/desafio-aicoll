<?php

namespace App\Application\Empresa\Get;

use App\Application\Query;

class GetEmpresaQuery implements Query
{
    public function __construct(
        public readonly string $nit // NIT de la empresa a buscar
    ) {}

    public function toArray(): array
    {
        return [
            'nit' => $this->nit,
        ];
    }
}
