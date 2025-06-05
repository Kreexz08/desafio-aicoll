<?php

namespace App\Presenter\Http\Empresa\Get;

use App\Application\Empresa\Get\GetAllEmpresasQuery;
use App\Application\Empresa\Get\GetAllEmpresasQueryHandler;
use Illuminate\Http\JsonResponse;

class ListEmpresasController
{
    public function __construct(
        private readonly GetAllEmpresasQueryHandler $handler
    ) {}

    public function __invoke(ListEmpresasRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $result = $this->handler->handle($query);

        $data = collect($result instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $result->items() : $result)
            ->map(fn($empresa) => $empresa->toArray());

        return response()->json([
            'data' => $data,
            'pagination' => $result instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? [
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
            ] : null,
        ]);
    }
}
