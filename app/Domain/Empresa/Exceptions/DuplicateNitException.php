<?php

namespace App\Domain\Empresa\Exceptions;

use Exception;

class DuplicateNitException extends EmpresaBusinessException
{
    public function __construct(string $message = 'El NIT ya existe', int $code = 409, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withNit(string $nit): self
    {
        return new self("Ya existe una empresa registrada con el NIT: {$nit}");
    }
}
