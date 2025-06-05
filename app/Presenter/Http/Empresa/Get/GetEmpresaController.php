<?php

namespace App\Presenter\Http\Empresa\Get;

use App\Application\Empresa\Get\GetEmpresaQuery;
use App\Application\Empresa\Get\GetEmpresaQueryHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class GetEmpresaController extends Controller
{
    public function __construct(
        private readonly GetEmpresaQueryHandler $handler
    ) {}

    public function __invoke(string $nit): JsonResponse
    {

        $query = new GetEmpresaQuery($nit);
        $empresa = $this->handler->handle($query);

        return response()->json($empresa->toArray());
    }
}
