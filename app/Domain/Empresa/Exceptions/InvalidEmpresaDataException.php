<?php

namespace App\Domain\Empresa\Exceptions;

use Exception;

class InvalidEmpresaDataException extends EmpresaBusinessException
{
    public function __construct(string $message = 'Datos de empresa inválidos', int $code = 400, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function invalidNit(string $nit): self
    {
        return new self("El NIT '{$nit}' no tiene un formato válido");
    }

    public static function invalidEstado(string $estado): self
    {
        return new self("El estado '{$estado}' no es válido. Los estados permitidos son: Activo, Inactivo");
    }

    public static function emptyField(string $field): self
    {
        return new self("El campo '{$field}' no puede estar vacío");
    }

    public static function fieldTooLong(string $field, int $maxLength): self
    {
        return new self("El campo '{$field}' no puede tener más de {$maxLength} caracteres");
    }

    public static function fieldLength(string $fieldName, int $expectedLength, int $actualLength): self
    {
        return new self(
            "El campo '{$fieldName}' debe tener exactamente {$expectedLength} caracteres, pero se recibieron {$actualLength}."
        );
    }
}
