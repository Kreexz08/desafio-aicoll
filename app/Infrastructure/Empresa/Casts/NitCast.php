<?php

namespace App\Infrastructure\Empresa\Casts;

use App\Domain\Empresa\ValueObjects\Nit;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class NitCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        return new Nit((string) $value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        if (!$value instanceof Nit) {
            try {
                $value = new Nit((string) $value);
            } catch (\Exception $e) {
                throw new InvalidArgumentException("El valor '{$value}' proporcionado para el NIT no es válido: " . $e->getMessage());
            }
        }
        return $value->getValue();
    }
}
