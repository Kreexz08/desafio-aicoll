<?php

namespace App\Application\Empresa\Delete; // Namespace ajustado

use App\Application\Command;
use App\Application\CommandHandler;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use InvalidArgumentException;

class DeleteInactiveEmpresasCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $empresaRepository
    ) {}

    public function handle(Command $command): int
    {
        if (!$command instanceof DeleteInactiveEmpresasCommand) {
            throw new InvalidArgumentException('Se esperaba un comando de tipo DeleteInactiveEmpresasCommand.');
        }
        return $this->empresaRepository->deleteInactives();
    }
}
