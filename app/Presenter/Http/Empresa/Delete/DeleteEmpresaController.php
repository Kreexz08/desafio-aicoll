<?php

namespace App\Presenter\Http\Empresa\Delete;

use App\Application\Empresa\Delete\DeleteEmpresaCommand;
use App\Application\Empresa\Delete\DeleteEmpresaCommandHandler;
use Symfony\Component\HttpFoundation\Response;

class DeleteEmpresaController
{
    public function __construct(
        private readonly DeleteEmpresaCommandHandler $handler
    ) {}

    public function __invoke(string $nit): Response
    {
        $command = new DeleteEmpresaCommand($nit);
        $this->handler->handle($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
