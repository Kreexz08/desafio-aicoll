<?php

use Illuminate\Support\Facades\Route;
use App\Presenter\Http\Empresa\Create\CreateEmpresaController;
use App\Presenter\Http\Empresa\Update\UpdateEmpresaController;
use App\Presenter\Http\Empresa\Delete\DeleteEmpresaController;
use App\Presenter\Http\Empresa\Get\GetEmpresaController;
use App\Presenter\Http\Empresa\Get\ListEmpresasController;
use App\Presenter\Http\Empresa\Delete\DeleteInactiveEmpresasController;

Route::prefix('empresas')->group(function () {
    Route::post('', CreateEmpresaController::class);
    Route::put('{nit}', UpdateEmpresaController::class);
    Route::delete('{nit}', DeleteEmpresaController::class);
    Route::get('{nit}', GetEmpresaController::class);
    Route::get('', ListEmpresasController::class);
    Route::post('delete-inactive', DeleteInactiveEmpresasController::class);
});
