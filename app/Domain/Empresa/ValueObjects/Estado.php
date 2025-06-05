<?php

namespace App\Domain\Empresa\ValueObjects;


use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use App\Infrastructure\Empresa\Casts\EstadoCast;
use Illuminate\Contracts\Database\Eloquent\Castable;

class Estado implements Castable
{
    public const ACTIVO = 'Activo';
    public const INACTIVO = 'Inactivo';

    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        if (!in_array($value, self::getValidStates(), true)) {
            throw InvalidEmpresaDataException::invalidEstado($value);
        }
    }

    public static function activo(): self
    {
        return new self(self::ACTIVO);
    }

    public static function inactivo(): self
    {
        return new self(self::INACTIVO);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isActivo(): bool
    {
        return $this->value === self::ACTIVO;
    }

    public function isInactivo(): bool
    {
        return $this->value === self::INACTIVO;
    }

    public function equals(Estado $other): bool
    {
        return $this->value === $other->value;
    }

    public static function getValidStates(): array
    {
        return [self::ACTIVO, self::INACTIVO];
    }

    public function __toString(): string
    {
        return $this->value;
    }


    public static function castUsing(array $arguments): string
    {
        return EstadoCast::class;
    }
}
