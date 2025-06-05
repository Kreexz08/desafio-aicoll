<?php

namespace App\Presenter\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Domain\Empresa\Exceptions\EmpresaBusinessException;
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


class Handler extends ExceptionHandler
{

    protected $dontReport = [
        // \App\Domain\Empresa\Exceptions\EmpresaBusinessException::class, // Ejemplo: no reportar excepciones de negocio si son esperadas
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {});

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {

                if ($e instanceof EmpresaBusinessException) {
                    $data = $e->getErrorData();
                    $statusCode = $e->getCode() ?: 422;
                    if (!is_int($statusCode) || $statusCode < 100 || $statusCode > 599) {
                        $statusCode = 422;
                    }
                    return new JsonResponse($data, $statusCode);
                }

                if ($e instanceof EmpresaNotFoundException) {
                    $statusCode = $e->getCode() ?: 404;
                     if (!is_int($statusCode) || $statusCode < 100 || $statusCode > 599) {
                        $statusCode = 404;
                    }
                    return new JsonResponse(
                        [
                            'error_type' => class_basename($e),
                            'error_code' => $statusCode,
                            'error_message' => $e->getMessage(),
                        ],
                        $statusCode
                    );
                }

                if ($e instanceof ValidationException) {
                    return new JsonResponse(
                        [
                            'message' => $e->getMessage(),
                            'errors' => $e->errors(),
                        ],
                        $e->status
                    );
                }

                if ($this->isHttpException($e)) {
                    /** @var HttpExceptionInterface $e */
                    $statusCode = $e->getStatusCode();
                    $message = $e->getMessage();

                    if (empty($message)) {
                        switch ($statusCode) {
                            case 401:
                                $message = 'No autenticado.';
                                break;
                            case 403:
                                $message = 'Acceso denegado.';
                                break;
                            case 404:
                                $message = 'Recurso no encontrado.';
                                break;
                            case 419:
                                $message = 'La sesión ha expirado o la página ha expirado (CSRF).';
                                break;
                            case 429:
                                $message = 'Demasiadas solicitudes.';
                                break;
                            case 503:
                                $message = 'Servicio no disponible.';
                                break;
                            default:
                                $message = 'Error HTTP.';
                        }
                    }
                    return new JsonResponse(['error_message' => $message], $statusCode);
                }

                $statusCode = 500;
                $responsePayload = ['error_message' => 'Error interno del servidor.'];

                if (config('app.debug')) {
                    $responsePayload['exception'] = get_class($e);
                    $responsePayload['message_debug'] = $e->getMessage();
                    $responsePayload['trace'] = $e->getTraceAsString();
                }
                return new JsonResponse($responsePayload, $statusCode);
            }

            return parent::render($request, $e);
        });
    }
}
