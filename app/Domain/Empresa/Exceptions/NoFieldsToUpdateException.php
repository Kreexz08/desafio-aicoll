<?php

namespace App\Domain\Empresa\Exceptions;

class NoFieldsToUpdateException extends EmpresaBusinessException
{
    public function __construct(string $message = 'No hay campos para actualizar', int $code = 422, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(): self
    {
        return new self('No se recibieron datos para actualizar la empresa');
    }
}
