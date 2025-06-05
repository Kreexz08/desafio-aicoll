<?php

namespace App\Application\Empresa\Get;

use App\Application\Query;
use App\Application\QueryHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use InvalidArgumentException;

class GetEmpresaQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $empresaRepository
    ) {}

    public function handle(Query $query): Empresa
    {
        if (!$query instanceof GetEmpresaQuery) {
            throw new InvalidArgumentException('Se esperaba una query de tipo GetEmpresaQuery.');
        }

        try {
            $nitVO = new Nit($query->nit);
        } catch (InvalidEmpresaDataException $e) {
            throw new InvalidEmpresaDataException("El formato del NIT proporcionado no es válido: {$e->getMessage()}");
        }

        $empresa = $this->empresaRepository->findByNit($nitVO);
        if (!$empresa) {
            throw new EmpresaNotFoundException("No se encontró empresa con el NIT: {$nitVO->getValue()}");
        }

        return $empresa;
    }
}
