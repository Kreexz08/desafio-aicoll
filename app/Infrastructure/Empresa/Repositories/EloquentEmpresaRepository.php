<?php

namespace App\Infrastructure\Empresa\Repositories;

use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado; 
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Infrastructure\Empresa\Models\EmpresaModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use ReflectionClass;

class EloquentEmpresaRepository implements EmpresaRepositoryInterface
{
    public function findById(int $id): ?Empresa
    {
        $model = EmpresaModel::find($id);

        if (!$model) {
            throw EmpresaNotFoundException::withId($id);
        }
        return $this->mapModelToEntity($model);
    }

    public function findByNit(Nit $nit): ?Empresa
    {
        $model = EmpresaModel::where('nit', $nit->getValue())->first();

        if (!$model) {
            return null;
        }
        return $this->mapModelToEntity($model);
    }

    public function save(Empresa $empresa): Empresa
    {
        $model = $empresa->getId()
            ? EmpresaModel::find($empresa->getId())
            : new EmpresaModel();

        if ($empresa->getId() && !$model) {
            throw new EmpresaNotFoundException("No se puede actualizar. Empresa con ID {$empresa->getId()} no encontrada.");
        }

        $model->nit = $empresa->getNit()->getValue();
        $model->nombre = $empresa->getNombre();
        $model->direccion = $empresa->getDireccion();
        $model->telefono = $empresa->getTelefono();
        $model->estado = $empresa->getEstado()->getValue();

        $model->save();

        if (!$empresa->getId() || $empresa->getCreatedAt() === null) {

            $reflection = new ReflectionClass($empresa);

            if (!$empresa->getId()) {
                $idProperty = $reflection->getProperty('id');
                $idProperty->setAccessible(true);
                $idProperty->setValue($empresa, $model->id);
            }

            $createdAtProperty = $reflection->getProperty('createdAt');
            $createdAtProperty->setAccessible(true);
            $createdAtProperty->setValue($empresa, new \DateTimeImmutable($model->created_at->toDateTimeString()));
            $updatedAtProperty = $reflection->getProperty('updatedAt');
            $updatedAtProperty->setAccessible(true);
            $updatedAtProperty->setValue($empresa, new \DateTimeImmutable($model->updated_at->toDateTimeString()));
        }

        return $empresa;
    }

    public function delete(Empresa $empresa): bool
    {
        if (!$empresa->getId()) {
            return false;
        }
        $model = EmpresaModel::find($empresa->getId());

        if (!$model) {
            return false;
        }
        return (bool) $model->delete();
    }

    public function existsByNit(Nit $nit): bool
    {
        return EmpresaModel::where('nit', $nit->getValue())->exists();
    }

    public function findAll(?Estado $estado = null): array
    {
        $query = EmpresaModel::query();

        if ($estado !== null) {
            $query->where('estado', $estado->getValue());
        }
        $models = $query->get();
        return $models->map(fn($model) => $this->mapModelToEntity($model))->all();
    }

    public function findAllPaginated(?Estado $estado = null, int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        $query = EmpresaModel::query();

        if ($estado !== null) {
            $query->where('estado', $estado->getValue());
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        $paginator->getCollection()->transform(function (EmpresaModel $model) {
            return $this->mapModelToEntity($model);
        });

        return $paginator;
    }

    public function updateByNit(Nit $nit, array $data): Empresa
    {
        $model = EmpresaModel::where('nit', $nit->getValue())->first();

        if (!$model) {
            throw EmpresaNotFoundException::withNit($nit->getValue());
        }
        if (isset($data['estado']) && $data['estado'] instanceof Estado) {
            $data['estado'] = $data['estado']->getValue();
        }

        $model->update($data);
        return $this->mapModelToEntity($model->fresh());
    }

    public function deleteByNit(Nit $nit): bool
    {
        $model = EmpresaModel::where('nit', $nit->getValue())->first();
        if (!$model) {
            return false;
        }
        return (bool) $model->delete();
    }

    public function deleteInactives(): int
    {
        $count = EmpresaModel::where('estado', Estado::INACTIVO)
            ->delete();
        return $count;
    }

    private function mapModelToEntity(EmpresaModel $model): Empresa
    {
        return new Empresa(
            id: $model->id,
            nit: new Nit($model->nit),
            nombre: $model->nombre,
            direccion: $model->direccion,
            telefono: $model->telefono,
            estado: new Estado($model->estado),
            createdAt: new \DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: new \DateTimeImmutable($model->updated_at->toDateTimeString())
        );
    }
}
