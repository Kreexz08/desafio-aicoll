<?php

namespace App\Domain\Empresa\Exceptions;

use Exception;

class CannotDeleteActiveEmpresaException extends EmpresaBusinessException
{
    public function __construct(string $message = 'No se puede eliminar una empresa activa', int $code = 422, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withNit(string $nit): self
    {
        return new self("No se puede eliminar la empresa con NIT {$nit} porque está activa. Debe cambiar su estado a 'Inactivo' primero.");
    }
}
