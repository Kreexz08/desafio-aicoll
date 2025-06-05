<?php

namespace App\Domain\Empresa\ValueObjects;


use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use App\Infrastructure\Empresa\Casts\NitCast;
use Illuminate\Contracts\Database\Eloquent\Castable;

class Nit implements Castable
{
    private string $value;

    public function __construct(string $value)
    {
        $cleanValue = $this->clean($value);
        $this->validate($cleanValue);
        $this->value = $cleanValue;
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw InvalidEmpresaDataException::emptyField('NIT');
        }

        if (strlen($value) !== 11) {
            throw InvalidEmpresaDataException::fieldLength('NIT', 11, strlen($value));
        }
        if (!preg_match('/^\d{9}-\d{1}$/', $value)) {
            throw InvalidEmpresaDataException::invalidNit($value);
        }
    }

    private function clean(string $value): string
    {
        return trim($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getNumericValue(): string
    {
        return preg_replace('/[^\d]/', '', $this->value);
    }

    public function equals(Nit $other): bool
    {
        return $this->getNumericValue() === $other->getNumericValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function castUsing(array $arguments): string
    {
        return NitCast::class;
    }
}
