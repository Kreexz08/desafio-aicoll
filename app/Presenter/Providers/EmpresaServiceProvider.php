<?php

namespace App\Infrastructure\Empresa\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Infrastructure\Empresa\Repositories\EloquentEmpresaRepository;

class EmpresaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EmpresaRepositoryInterface::class, EloquentEmpresaRepository::class);
    }

    public function boot()
    {
        //
    }
}
