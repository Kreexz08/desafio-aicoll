<?php

namespace App\Domain\Empresa\Interfaces;

use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EmpresaRepositoryInterface
{
    public function save(Empresa $empresa): Empresa;

    public function findByNit(Nit $nit): ?Empresa;

    public function findById(int $id): ?Empresa;

    public function existsByNit(Nit $nit): bool;

    public function findAll(?Estado $estado = null): array;

    public function findAllPaginated(?Estado $estado = null, int $page = 1, int $perPage = 15): LengthAwarePaginator;

    public function updateByNit(Nit $nit, array $data): Empresa;

    public function delete(Empresa $empresa): bool;

    public function deleteByNit(Nit $nit): bool;

    public function deleteInactives(): int;
}
