<?php

namespace App\Presenter\Http\Empresa\Create;

use App\Application\Empresa\Create\CreateEmpresaCommandHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateEmpresaController
{
    public function __construct(
        private readonly CreateEmpresaCommandHandler $handler
    ) {}

    public function __invoke(CreateEmpresaRequest $request): JsonResponse
    {
        $command = $request->toCommand();
        $empresa = $this->handler->handle($command);
        return new JsonResponse($empresa->toArray(), JsonResponse::HTTP_CREATED);
    }
}
