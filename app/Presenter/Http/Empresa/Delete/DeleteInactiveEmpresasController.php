<?php


namespace App\Presenter\Http\Empresa\Delete;


use App\Application\Empresa\Delete\DeleteInactiveEmpresasCommand;
use App\Application\Empresa\Delete\DeleteInactiveEmpresasCommandHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteInactiveEmpresasController
{
    public function __construct(
        private readonly DeleteInactiveEmpresasCommandHandler $handler
    ) {}

    public function __invoke(): JsonResponse
    {
        $command = new DeleteInactiveEmpresasCommand();
        $count = $this->handler->handle($command);
        return new JsonResponse(
            ['message' => "Se eliminaron {$count} empresas inactivas."],
            Response::HTTP_OK
        );
    }
}
