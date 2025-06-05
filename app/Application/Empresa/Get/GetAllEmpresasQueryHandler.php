<?php

namespace App\Application\Empresa\Get;

use App\Application\Query;
use App\Application\QueryHandler;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Empresa\ValueObjects\Estado;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use InvalidArgumentException;

class GetAllEmpresasQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $empresaRepository
    ) {}

    public function handle(Query $query): LengthAwarePaginator|array
    {
        if (!$query instanceof GetAllEmpresasQuery) {
            throw new InvalidArgumentException('Se esperaba una query de tipo GetAllEmpresasQuery.');
        }

        $estadoVO = null;
        if ($query->estado !== null) {
            try {
                $estadoVO = new Estado($query->estado);
            } catch (InvalidEmpresaDataException $e) {
                throw new InvalidEmpresaDataException("El valor para el estado del filtro no es válido: {$query->estado}. {$e->getMessage()}");
            }
        }
        if ($query->page !== null && $query->perPage !== null) {
            return $this->empresaRepository->findAllPaginated(
                estado: $estadoVO,
                page: $query->page,
                perPage: $query->perPage
            );
        }
        $result = $this->empresaRepository->findAll(estado: $estadoVO);
        return $result;
    }
}
