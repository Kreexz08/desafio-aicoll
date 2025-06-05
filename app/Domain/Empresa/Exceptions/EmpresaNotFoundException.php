<?php

namespace App\Domain\Empresa\Exceptions;

use Exception;

class EmpresaNotFoundException extends Exception 
{
    public function __construct(string $message = 'Empresa no encontrada', int $code = 404, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withNit(string $nit): self
    {
        return new self("No se encontró empresa con el NIT: {$nit}");
    }

    public static function withId(int $id): self
    {
        return new self("No se encontró empresa con el ID: {$id}");
    }
}
