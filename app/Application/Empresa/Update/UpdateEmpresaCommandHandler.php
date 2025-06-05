<?php

namespace App\Application\Empresa\Update;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use App\Domain\Empresa\Exceptions\NoFieldsToUpdateException;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use InvalidArgumentException;


class UpdateEmpresaCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $empresaRepository
    ) {}

    public function handle(Command $command): Empresa
    {
        if (!$command instanceof UpdateEmpresaCommand) {
            throw new InvalidArgumentException('Se esperaba un comando de tipo UpdateEmpresaCommand.');
        }

        try {
            $nitVO = new Nit($command->nit);
        } catch (InvalidEmpresaDataException $e) {
            throw new InvalidEmpresaDataException("El formato del NIT proporcionado no es válido: {$e->getMessage()}");
        }

        $empresa = $this->empresaRepository->findByNit($nitVO);

        if (!$empresa) {
            throw new EmpresaNotFoundException("No se encontró empresa con el NIT: {$nitVO->getValue()}");
        }

        if (!$command->hasDataToUpdate()) {
            throw new NoFieldsToUpdateException("No se proporcionaron datos para actualizar para la empresa con NIT: {$nitVO->getValue()}.");
        }

        if ($command->nombre !== null) {
            $empresa->updateNombre($command->nombre);
        }

        if ($command->direccion !== null) {
            $empresa->updateDireccion($command->direccion);
        }

        if ($command->telefono !== null) {
            $empresa->updateTelefono($command->telefono);
        }

        if ($command->estado !== null) {
            try {
                $estadoVO = new Estado($command->estado);
                $empresa->updateEstado($estadoVO);
            } catch (InvalidEmpresaDataException $e) {
                throw new InvalidEmpresaDataException("El valor para el estado no es válido: {$command->estado}. {$e->getMessage()}");
            }
        }
        return $this->empresaRepository->save($empresa);
    }
}
