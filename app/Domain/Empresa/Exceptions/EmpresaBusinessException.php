<?php
namespace App\Domain\Empresa\Exceptions;
use Exception;

abstract class EmpresaBusinessException extends Exception
{
    public function __construct(string $message, int $code = 422, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorData(): array
    {
        return [
            'error_type' => static::class,
            'error_code' => $this->getCode(),
            'error_message' => $this->getMessage(),
        ];
    }
}
