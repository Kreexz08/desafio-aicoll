<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions as ExceptionsConfigurator; // Renombrado para evitar colisión si usas Exceptions como clase
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Contracts\Debug\ExceptionHandler; // Importar el contrato

// --- PASO 1: Configurar y crear la aplicación ---
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../app/Presenter/Http/Routes/web.php',
        api: __DIR__.'/../app/Presenter/Http/Routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Puedes configurar middleware global aquí si lo necesitas
        // Ejemplo: $middleware->append(MiMiddlewareGlobal::class);
    })
    ->withExceptions(function (ExceptionsConfigurator $exceptions) {
        // En este bloque, puedes AÑADIR lógica específica de renderizado o reporte
        // que se aplicará a la instancia del manejador de excepciones que Laravel resuelva.
        // Por ejemplo, si quisieras añadir un renderizador específico aquí:
        //
        // $exceptions->render(function (\App\Domain\AlgunaOtraExcepcion $e, $request) {
        // if ($request->expectsJson()) {
        // return response()->json(['message' => 'Error específico: ' . $e->getMessage()], 456);
        //     }
        // });
        //
        // Si toda tu lógica está en el método register() de tu clase Handler personalizada,
        // este bloque puede quedar vacío o usarse para ajustes menores.
        // La vinculación que haremos abajo se encargará de que se use tu clase Handler.

        // Por ahora, para la depuración, lo dejamos vacío o con tu dd() si quieres probar si este bloque se ejecuta.
        // dd('Bloque withExceptions en bootstrap/app.php alcanzado');

    })
    ->create(); // Aquí se crea la instancia de la aplicación ($app)

// --- PASO 2: Sobreescribir la vinculación del ExceptionHandler ---
// Asegúrate de que la clase y el namespace sean correctos para TU manejador.
// Si tu manejador está en App\Presenter\Exceptions\Handler.php:
$handlerClass = \App\Presenter\Exceptions\Handler::class;

// Si tu manejador está en la ubicación convencional App\Exceptions\Handler.php:
// $handlerClass = \App\Exceptions\Handler::class;

// Verifica que la clase que vas a usar realmente exista
if (!class_exists($handlerClass)) {
    // Puedes lanzar un error aquí, o loguearlo, o recurrir a un default
    // Esto es solo una salvaguarda durante la configuración.
    // En producción, deberías asegurarte de que siempre exista.
    throw new \RuntimeException("La clase manejadora de excepciones especificada no existe: " . $handlerClass);
}

$app->singleton(
    ExceptionHandler::class, // El contrato que Laravel usa para resolver el manejador
    $handlerClass           // Tu clase Handler personalizada
);

// --- PASO 3: Devolver la aplicación ---
return $app;
