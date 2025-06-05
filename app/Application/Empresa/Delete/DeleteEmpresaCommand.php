<?php

namespace App\Application\Empresa\Delete;

use App\Application\Command;

class DeleteEmpresaCommand implements Command
{
    public function __construct(
        public readonly string $nit
    ) {}

    public function toArray(): array
    {
        return [
            'nit' => $this->nit,
        ];
    }
}
