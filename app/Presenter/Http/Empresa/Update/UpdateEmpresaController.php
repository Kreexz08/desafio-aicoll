<?php

namespace App\Presenter\Http\Empresa\Update;

use App\Application\Empresa\Update\UpdateEmpresaCommand; 
use App\Application\Empresa\Update\UpdateEmpresaCommandHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateEmpresaController
{
    public function __construct(
        private readonly UpdateEmpresaCommandHandler $handler
    ) {}

    public function __invoke(string $nit, UpdateEmpresaRequest $request): JsonResponse
    {
        $command = $request->toCommand($nit);
        $empresa = $this->handler->handle($command);

        return new JsonResponse($empresa->toArray());
    }
}
