<?php

declare(strict_types=1);

namespace App\Presenter\Providers;

use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Infrastructure\Empresa\Repositories\EloquentEmpresaRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EmpresaRepositoryInterface::class, EloquentEmpresaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
