<?php

namespace App\Application\Empresa\Delete;

use App\Application\Command; 
use App\Application\CommandHandler;
use App\Domain\Empresa\Exceptions\CannotDeleteActiveEmpresaException;
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use InvalidArgumentException;

class DeleteEmpresaCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $empresaRepository
    ) {}

    public function handle(Command $command): bool
    {
        if (!$command instanceof DeleteEmpresaCommand) {
            throw new InvalidArgumentException('Se esperaba un comando de tipo DeleteEmpresaCommand.');
        }

        try {
            $nitVO = new Nit($command->nit);
        } catch (InvalidEmpresaDataException $e) {
            throw $e;
        }

        $empresa = $this->empresaRepository->findByNit($nitVO);

        if (!$empresa) {
            throw new EmpresaNotFoundException("No se encontró empresa con el NIT: {$nitVO->getValue()}");
        }

        if ($empresa->isActive()) {
            throw new CannotDeleteActiveEmpresaException(
                "No se puede eliminar la empresa con NIT {$nitVO->getValue()} porque está activa. Debe cambiar su estado a 'Inactivo' primero."
            );
        }
        return $this->empresaRepository->delete($empresa);
    }
}
