<?php

namespace App\Application\Empresa\Delete;

use App\Application\Command;

class DeleteInactiveEmpresasCommand implements Command
{
    public function __construct() {}

    public function toArray(): array
    {
        return [];
    }
}
