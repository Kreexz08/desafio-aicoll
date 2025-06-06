<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions as ExceptionsConfigurator;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Contracts\Debug\ExceptionHandler;


$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../app/Presenter/Http/Routes/web.php',
        api: __DIR__.'/../app/Presenter/Http/Routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

    })
    ->withExceptions(function (ExceptionsConfigurator $exceptions) {

    })
    ->create(); // Aquí se crea la instancia de la aplicación ($app)

$handlerClass = \App\Presenter\Exceptions\Handler::class;


// Verifica que la clase que vas a usar realmente exista
if (!class_exists($handlerClass)) {
    throw new \RuntimeException("La clase manejadora de excepciones especificada no existe: " . $handlerClass);
}

$app->singleton(
    ExceptionHandler::class, // El contrato que Laravel usa para resolver el manejador
    $handlerClass           // Tu clase Handler personalizada
);


return $app;
