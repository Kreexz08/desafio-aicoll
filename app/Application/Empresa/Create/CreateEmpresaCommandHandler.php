<?php

namespace App\Application\Empresa\Create;

use App\Application\Command; 
use App\Application\CommandHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Exceptions\DuplicateNitException;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use InvalidArgumentException;

class CreateEmpresaCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $empresaRepository
    ) {}

    public function handle(Command $command): Empresa
    {
        if (!$command instanceof CreateEmpresaCommand) {
            throw new InvalidArgumentException('Se esperaba un comando de tipo CreateEmpresaCommand.');
        }
        try {
            $nitVO = new Nit($command->nit);
            $estadoVO = new Estado($command->estado);
        } catch (InvalidEmpresaDataException $e) {
            throw $e;
        }

        if ($this->empresaRepository->existsByNit($nitVO)) {
            throw new DuplicateNitException("Ya existe una empresa con el NIT: {$nitVO->getValue()}");
        }
        $empresa = new Empresa(
            nit: $nitVO,
            nombre: $command->nombre,
            direccion: $command->direccion,
            telefono: $command->telefono,
            estado: $estadoVO
        );

        return $this->empresaRepository->save($empresa);
    }
}
