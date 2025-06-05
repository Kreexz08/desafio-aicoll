<?php

namespace App\Domain\Empresa\Casts;

use App\Domain\Empresa\ValueObjects\Estado;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class EstadoCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        return new Estado((string) $value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        if (!$value instanceof Estado) {
           try {
                $value = new Estado((string) $value);
            } catch (\Exception $e) {
                throw new InvalidArgumentException("El valor '{$value}' proporcionado para el Estado no es válido: " . $e->getMessage());
            }
        }

        return $value->getValue();
    }
}
